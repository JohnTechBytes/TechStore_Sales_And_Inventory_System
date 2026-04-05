<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;

class Dashboard extends BaseController
{
    protected $productModel;
    protected $saleModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->saleModel = new SaleModel();
    }

    public function index()
    {

        // Total products
        $data['total_products'] = $this->productModel->countAll();
        
        // Low stock count
        $data['low_stock'] = $this->productModel->where('stock <= min_stock')->countAll();
        
        // Low stock list for table
        $data['low_stock_list'] = $this->productModel->where('stock <= min_stock')->findAll();
        
        // Today's sales
        $todaySales = $this->saleModel->where('DATE(sale_date)', date('Y-m-d'))->selectSum('grand_total')->first();
        $data['today_sales'] = $todaySales['grand_total'] ?? 0;
        
        // This month sales
        $monthSales = $this->saleModel->where('MONTH(sale_date)', date('m'))
                                      ->where('YEAR(sale_date)', date('Y'))
                                      ->selectSum('grand_total')
                                      ->first();
        $data['month_sales'] = $monthSales['grand_total'] ?? 0;
        
        // Monthly sales for chart (last 12 months)
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $sales = $this->saleModel->where('MONTH(sale_date)', $i)
                                     ->where('YEAR(sale_date)', date('Y'))
                                     ->selectSum('grand_total')
                                     ->first();
            $monthlySales[] = $sales['grand_total'] ?? 0;
        }
        $data['monthly_sales'] = $monthlySales;
        
        // Top 5 selling products
        $db = \Config\Database::connect();
        $data['top_products'] = $db->table('sale_items')
            ->select('products.name, SUM(sale_items.quantity) as total_sold')
            ->join('products', 'products.id = sale_items.product_id')
            ->groupBy('sale_items.product_id')
            ->orderBy('total_sold', 'DESC')
            ->limit(5)
            ->get()
            ->getResult();

            
        
        return view('dashboard/index', $data);
    }
}