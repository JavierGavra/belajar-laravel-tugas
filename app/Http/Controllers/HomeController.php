<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $data['products'] = Product::all();

        return view('v_home', $data);
    }
}
