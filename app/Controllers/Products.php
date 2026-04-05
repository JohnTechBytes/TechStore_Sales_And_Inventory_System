<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\LogModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        // Restrict to admin only
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Admin access only');
        }
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display list of products
     */
    public function index()
    {
        $data['products'] = $this->productModel->getProductsWithCategory();
        return view('products/index', $data);
    }

    /**
     * Show form to create new product
     */
  public function create()
{
    $data['categories'] = $this->categoryModel->findAll();
    $data['selected_category'] = $this->request->getGet('category_id');
    return view('products/form', $data);
}

    /**
     * Store new product
     */
    public function store()
    {
        $rules = [
            'name'          => 'required',
            'sku'           => 'required|is_unique[products.sku]',
            'selling_price' => 'required|numeric',
            'stock'         => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle image upload
        $image = $this->uploadImage();

        // Convert empty category to NULL
        $category_id = $this->request->getPost('category_id');
        if (empty($category_id)) {
            $category_id = null;
        }

        $this->productModel->save([
            'name'          => $this->request->getPost('name'),
            'sku'           => $this->request->getPost('sku'),
            'category_id'   => $category_id,
            'buying_price'  => $this->request->getPost('buying_price'),
            'selling_price' => $this->request->getPost('selling_price'),
            'stock'         => $this->request->getPost('stock'),
            'min_stock'     => $this->request->getPost('min_stock'),
            'image'         => $image,
            'status'        => $this->request->getPost('status')
        ]);

        // Log activity
        $logModel = new LogModel();
        $logModel->addLog('Added product: ' . $this->request->getPost('name'), 'PRODUCT');

        return redirect()->to('/products')->with('message', 'Product added successfully');
    }

    /**
     * Show form to edit product
     */
    public function edit($id)
{
    $data['product'] = $this->productModel->find($id);
    $data['categories'] = $this->categoryModel->findAll();
    if (!$data['product']) {
        return redirect()->to('/products')->with('error', 'Product not found');
    }
    return view('products/form', $data);
}

    /**
     * Update product
     */
    public function update($id)
{
    $rules = [
        'name' => 'required',
        'selling_price' => 'required|numeric'
    ];
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $product = $this->productModel->find($id);
    if (!$product) {
        return redirect()->to('/products')->with('error', 'Product not found');
    }

    $image = $product['image'];
    $newImage = $this->uploadImage();
    if ($newImage) {
        if (!empty($product['image']) && file_exists('uploads/products/' . $product['image'])) {
            unlink('uploads/products/' . $product['image']);
        }
        $image = $newImage;
    }

    $category_id = $this->request->getPost('category_id');
    if (empty($category_id)) {
        $category_id = null;
    }

    // IMPORTANT: include 'stock' in the update array
    $this->productModel->update($id, [
        'name'          => $this->request->getPost('name'),
        'category_id'   => $category_id,
        'buying_price'  => $this->request->getPost('buying_price'),
        'selling_price' => $this->request->getPost('selling_price'),
        'stock'         => $this->request->getPost('stock'),        // <-- added
        'min_stock'     => $this->request->getPost('min_stock'),
        'image'         => $image,
        'status'        => $this->request->getPost('status')
    ]);

    $logModel = new LogModel();
    $logModel->addLog('Updated product: ' . $this->request->getPost('name'), 'PRODUCT');

    return redirect()->to('/products')->with('message', 'Product updated');
}

    /**
     * Delete product (and its image file)
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }

        // Delete image file if exists
        if (!empty($product['image']) && file_exists('uploads/products/' . $product['image'])) {
            unlink('uploads/products/' . $product['image']);
        }

        $this->productModel->delete($id);

        $logModel = new LogModel();
        $logModel->addLog('Deleted product: ' . $product['name'], 'PRODUCT');

        return redirect()->to('/products')->with('message', 'Product deleted');
    }

    /**
     * Helper: Upload product image
     * @return string|null
     */
    private function uploadImage()
    {
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/products', $newName);
            return $newName;
        }
        return null;
    }
}