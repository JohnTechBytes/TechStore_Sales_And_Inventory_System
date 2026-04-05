<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\SaleItemModel;
use App\Models\LogModel;           // <-- add this

class Reports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Monthly sales chart data
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $saleModel = new SaleModel();
            $sales = $saleModel->where('MONTH(sale_date)', $i)
                               ->where('YEAR(sale_date)', date('Y'))
                               ->selectSum('grand_total')
                               ->first();
            $monthlySales[] = $sales['grand_total'] ?? 0;
        }
        $data['monthly_sales'] = json_encode($monthlySales);

        // Top 10 products
        $data['top_products'] = $db->table('sale_items')
            ->select('products.name, SUM(sale_items.quantity) as total_sold, SUM(sale_items.total) as revenue')
            ->join('products', 'products.id = sale_items.product_id')
            ->groupBy('sale_items.product_id')
            ->orderBy('revenue', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();

        // Low stock products (FIXED)
        $productModel = new ProductModel();
        $data['low_stock'] = $productModel->where('stock <= min_stock')->where('status', 'active')->findAll();

        // Activity logs
        $logModel = new LogModel();
        $data['activity_logs'] = $logModel->getRecentLogs(20);

        return view('reports/index', $data);
    }

}