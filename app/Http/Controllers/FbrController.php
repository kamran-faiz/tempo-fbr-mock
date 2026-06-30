<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FbrController extends Controller
{
    public function submit(Request $request){
        $request->validate([
           'invoice_number' => 'required|string',
           'business_id'  => 'required|string',
           'ntn' => 'string|nullable',
            'cnic' => 'string|nullable',
            'amount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric',
            'items.*.tax' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0'
        ]);
    }
}
