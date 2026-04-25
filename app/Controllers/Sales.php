<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\StockMovementModel;
use App\Models\LogModel;

class Sales extends BaseController
{
    // -----------------------------------------------------------------
    // POINT OF SALE – main view
    // -----------------------------------------------------------------
    public function pos()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->where('stock >', 0)->where('status', 'active')->findAll();
        // Pass the current cart to the view (used for initial render)
        $data['cart'] = session()->get('cart') ?? [];
        return view('sales/pos', $data);
    }

    // -----------------------------------------------------------------
    // AJAX: Add to cart
    // -----------------------------------------------------------------
   // Add to cart (POST) – redirects, no JSON
public function addToCart()
{
    $product_id = $this->request->getPost('product_id');
    $quantity = (int)$this->request->getPost('quantity');
    if ($quantity < 1) $quantity = 1;

    $productModel = new ProductModel();
    $product = $productModel->find($product_id);
    if (!$product) {
        return redirect()->to('/sales/pos')->with('error', 'Product not found');
    }

    $cart = session()->get('cart') ?? [];
    if (isset($cart[$product_id])) {
        $cart[$product_id]['qty'] += $quantity;
    } else {
        $cart[$product_id] = [
            'id'    => $product['id'],
            'name'  => $product['name'],
            'price' => (float)$product['selling_price'],
            'qty'   => $quantity
        ];
    }
    session()->set('cart', $cart);
    return redirect()->to('/sales/pos')->with('message', 'Product added to cart');
}

// Update cart (POST) – redirects
public function updateCart()
{
    $product_id = $this->request->getPost('product_id');
    $quantity = (int)$this->request->getPost('quantity');
    $cart = session()->get('cart') ?? [];

    if ($quantity <= 0) {
        unset($cart[$product_id]);
    } elseif (isset($cart[$product_id])) {
        $cart[$product_id]['qty'] = $quantity;
    }
    session()->set('cart', $cart);
    return redirect()->to('/sales/pos');
}

// Remove from cart (GET) – redirects
public function removeFromCart($product_id)
{
    $cart = session()->get('cart') ?? [];
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        session()->set('cart', $cart);
    }
    return redirect()->to('/sales/pos');
}
    // Checkout (non‑AJAX – uses standard POST)
    // -----------------------------------------------------------------
  public function checkout()
{
    // First, check if cart_data was sent (from client-side cart)
    $cartData = $this->request->getPost('cart_data');
    if ($cartData) {
        $cartArray = json_decode($cartData, true);
        if (empty($cartArray)) {
            return redirect()->to('/sales/pos')->with('error', 'Cart is empty');
        }
        // Convert to session format (same as your old addToCart)
        $sessionCart = [];
        foreach ($cartArray as $item) {
            $sessionCart[$item['id']] = [
                'id'    => $item['id'],
                'name'  => $item['name'],
                'price' => (float)$item['price'],
                'qty'   => (int)$item['qty']
            ];
        }
        session()->set('cart', $sessionCart);
    }

    // Now get cart from session (works for both old and new)
    $cart = session()->get('cart');
    if (empty($cart)) {
        return redirect()->to('/sales/pos')->with('error', 'Cart is empty');
    }

    // Calculate grand total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }

    $saleModel = new SaleModel();
    $invoice = 'INV-' . date('YmdHis') . rand(100, 999);

    $saleId = $saleModel->insert([
        'invoice_no'     => $invoice,
        'user_id'        => session()->get('user_id'),
        'customer_name'  => $this->request->getPost('customer_name'),
        'total_amount'   => $total,
        'discount'       => 0,
        'tax'            => 0,
        'grand_total'    => $total,
        'payment_method' => $this->request->getPost('payment_method'),
        'sale_date'      => date('Y-m-d H:i:s')
    ]);

    if (!$saleId) {
        return redirect()->to('/sales/pos')->with('error', 'Failed to create sale record');
    }

    $saleItemModel = new SaleItemModel();
    $productModel = new ProductModel();
    $stockMovementModel = new StockMovementModel();

    foreach ($cart as $item) {
        // Insert sale item
        $saleItemModel->insert([
            'sale_id'    => $saleId,
            'product_id' => $item['id'],
            'quantity'   => $item['qty'],
            'price'      => $item['price'],
            'total'      => $item['price'] * $item['qty']
        ]);

        // Reduce stock
        $product = $productModel->find($item['id']);
        if ($product) {
            $newStock = $product['stock'] - $item['qty'];
            $productModel->update($item['id'], ['stock' => $newStock]);
        }

        // Log stock movement
        $stockMovementModel->insert([
            'product_id' => $item['id'],
            'quantity'   => $item['qty'],
            'type'       => 'out',
            'reference'  => $invoice,
            'user_id'    => session()->get('user_id')
        ]);
    }

    // Log sale activity
    $logModel = new LogModel();
    $logModel->addLog('Sale completed: ' . $invoice, 'SALE');

    // Clear cart
    session()->remove('cart');

    return redirect()->to('/sales/receipt/' . $saleId)->with('message', 'Sale completed successfully');
}

    // -----------------------------------------------------------------
    // Receipt page
    // -----------------------------------------------------------------
  public function receipt($id)
{
    $db = \Config\Database::connect();
    $sale = $db->table('sales')->where('id', $id)->get()->getRowArray();
    if (!$sale) {
        return redirect()->to('/sales/history')->with('error', 'Receipt not found');
    }
    $items = $db->table('sale_items')
                ->select('sale_items.*, products.name')
                ->join('products', 'products.id = sale_items.product_id')
                ->where('sale_id', $id)
                ->get()
                ->getResultArray();
    return view('sales/receipt', ['sale' => $sale, 'items' => $items]);
}
    // -----------------------------------------------------------------
    // Sales history
    // -----------------------------------------------------------------
    public function history()
    {
        $saleModel = new SaleModel();
        $data['sales'] = $saleModel->orderBy('sale_date', 'DESC')->findAll();
        return view('sales/history', $data);
    }
public function processCheckout()
{
    // Enable error display temporarily
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    if ($this->request->getMethod() !== 'post') {
        return redirect()->to('/pos');
    }

    $cart = json_decode($this->request->getPost('cart_data'), true);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Cart is empty');
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
            // Deduct stock
            $db->table('products')->where('id', $item['id'])
               ->set('stock', 'stock - ' . (int)$item['qty'], false)
               ->update();
        }

        // Use EXACT same column names as your working `checkout` method
        $invoiceNo = 'INV-' . date('YmdHis') . rand(100, 999);
        $userId = session()->get('user_id');
        if (!$userId) {
            throw new \Exception('User not logged in (user_id missing)');
        }

        $insertData = [
            'invoice_no'     => $invoiceNo,
            'user_id'        => $userId,
            'customer_name'  => $this->request->getPost('customer_name'),
            'total_amount'   => $totalAmount,
            'discount'       => 0,
            'tax'            => 0,
            'grand_total'    => $totalAmount,
            'payment_method' => $this->request->getPost('payment_method'),
            'sale_date'      => date('Y-m-d H:i:s')
        ];

        if (!$db->table('sales')->insert($insertData)) {
            throw new \Exception('Failed to insert sale: ' . print_r($db->error(), true));
        }
        $saleId = $db->insertID();

        foreach ($cart as $item) {
            $db->table('sale_items')->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
                'total'      => $item['price'] * $item['qty']
            ]);
        }

        $db->transCommit();

        // Log sale
        $logModel = new LogModel();
        $logModel->addLog('Sale completed: ' . $invoiceNo, 'SALE');

        session()->remove('cart');

        // ✅ Redirect to receipt
        return redirect()->to('/sales/receipt/' . $saleId)
                         ->with('success', 'Sale completed successfully!');

    } catch (\Exception $e) {
        $db->transRollback();
        // Show error on screen for debugging (remove after fix)
        die('Checkout Error: ' . $e->getMessage());
    }
}
public function syncCart()
{
    $input = $this->request->getJSON(true);
    $cart = $input['cart'] ?? [];
    session()->remove('cart');
    foreach ($cart as $item) {
        session()->set('cart[' . $item['id'] . ']', [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'price' => $item['price'],
            'qty'   => $item['qty']
        ]);
    }
    return $this->response->setJSON(['success' => true]);
}
public function searchProducts()
{
    $query = $this->request->getGet('q');
    $db = \Config\Database::connect();
    $builder = $db->table('products')->select('id, name, selling_price, image');
    
    // If a search term is provided, filter by name
    if (!empty($query) && strlen(trim($query)) >= 1) {
        $builder->like('name', $query);
    }
    
    // Limit to avoid huge payload (adjust as needed)
    $products = $builder->limit(50)->get()->getResultArray();
    
    // If no products, return empty array
    if (empty($products)) {
        return $this->response->setJSON([]);
    }
    
    $data = [];
    foreach ($products as $p) {
        $imagePath = !empty($p['image']) ? 'uploads/products/' . $p['image'] : 'assets/img/no-image.png';
        $data[] = [
            'id'           => $p['id'],
            'name'         => $p['name'],
            'selling_price'=> (float)$p['selling_price'],
            'image_url'    => base_url($imagePath)
        ];
    }
    
    return $this->response->setJSON($data);
}
}