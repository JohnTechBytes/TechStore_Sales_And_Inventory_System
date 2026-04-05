<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogModel;

class Logs extends BaseController
{

    public function index()
{
    $logModel = new LogModel();
    
    // Get the 'date' from query string; if not set, use today's date
    $date = $this->request->getGet('date') ?? date('Y-m-d');

    $data['logs'] = $logModel->getLogsByDate($date);
    $data['selectedDate'] = $date;

    return view('log/index', $data);
}

}