<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function cart()
    {
        return view('cart');
    }

    public function index()
    {
        return view('checkout');
    }
}
