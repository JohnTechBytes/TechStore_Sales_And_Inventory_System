<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\LogModel;

class Categories extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') != 'admin') die(redirect()->to('/dashboard'));
    }

    public function index()
    {
        $model = new CategoryModel();
        $data['categories'] = $model->findAll();
        return view('categories/index', $data);
    }

    public function store()
    {
        $model = new CategoryModel();
        $model->save(['name' => $this->request->getPost('name'), 'description' => $this->request->getPost('description')]);
        $log = new LogModel();
        $log->addLog('Added category: ' . $this->request->getPost('name'), 'CATEGORY');
        return redirect()->to('/categories')->with('message', 'Category added');
    }

    public function update($id)
    {
        $model = new CategoryModel();
        $model->update($id, ['name' => $this->request->getPost('name'), 'description' => $this->request->getPost('description')]);
        $log = new LogModel();
        $log->addLog('Updated category: ' . $this->request->getPost('name'), 'CATEGORY');
        return redirect()->to('/categories')->with('message', 'Category updated');
    }

    public function delete($id)
    {
        $model = new CategoryModel();
        $model->delete($id);
        $log = new LogModel();
        $log->addLog('Deleted category ID: ' . $id, 'CATEGORY');
        return redirect()->to('/categories')->with('message', 'Category deleted');
    }
}