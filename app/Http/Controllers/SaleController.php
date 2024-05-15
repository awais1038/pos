<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{

    public function index()
    {
        echo 'hello';exit;
        
        return view('sale.index');
    }

    
}
