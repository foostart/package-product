<?php

namespace Foostart\Product\Controlers\User;

use App\Http\Controllers\Controller;
use Foostart\Product\Models\Product;
use Illuminate\Http\Request;

use URL,
    Route,
    Redirect;
//use Foostart\Sample\Models\Samples;

class ProductUserController extends Controller
{
    public $data = array();
    public function __construct() {

    }

    public function index(){
        // Get List of post
        $products = Product::get();
        return view('products.index', compact('products'));
    }

}