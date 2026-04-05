<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockMovementModel;
use App\Models\LogModel;

class Stock extends BaseController
{
    public function __construct()
    {
        // Restrict to admin only
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Admin access only');
        }
    }

    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->select('products.*, categories.name as category_name')
                                         ->join('categories', 'categories.id = products.category_id', 'left')
                                         ->findAll();
        return view('stock/index', $data);
    }

    public function addStock()
    {
        
        // Ensure it's a POST request
        if (!$this->request->is('POST')) {
            return redirect()->to('/stock')->with('error', 'Invalid request method.');
        }

        $product_id = $this->request->getPost('product_id');
        $quantity = (int)$this->request->getPost('quantity');
        $note = $this->request->getPost('note');

        // Validate input
        if (empty($product_id)) {
            return redirect()->back()->with('error', 'Product ID is missing.');
        }
        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be greater than zero.');
        }

        $productModel = new ProductModel();
        $product = $productModel->find($product_id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Update stock
        $newStock = $product['stock'] + $quantity;
        if (!$productModel->update($product_id, ['stock' => $newStock])) {
            return redirect()->back()->with('error', 'Failed to update stock.');
        }

        // Log stock movement
        $stockMovementModel = new StockMovementModel();
        $stockMovementModel->insert([
            'product_id' => $product_id,
            'quantity'   => $quantity,
            'type'       => 'in',
            'reference'  => $note,
            'user_id'    => session()->get('user_id')
        ]);

        // Log activity
        $logModel = new LogModel();
        $logModel->addLog("Added stock to {$product['name']}: +{$quantity}", 'STOCK');

        return redirect()->to('/stock')->with('message', 'Stock added successfully.');
    }
}