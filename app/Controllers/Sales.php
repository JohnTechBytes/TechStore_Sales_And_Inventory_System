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
        $saleModel = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $sale = $saleModel->find($id);
        if (!$sale) {
            return redirect()->to('/sales/history')->with('error', 'Receipt not found');
        }

        $items = $saleItemModel->select('sale_items.*, products.name')
                               ->join('products', 'products.id = sale_items.product_id')
                               ->where('sale_id', $id)
                               ->findAll();

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
}