<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FbrSubmission;
use Illuminate\Support\Str;

class FbrController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'business_id' => 'required|string',
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

        $isDuplicate = FbrSubmission::where('invoice_number', $request->invoice_number)->exists();
        if ($isDuplicate) {
            return response()->json([
                'success' => false,
                'message' => 'This invoice number has already been submitted.'
            ], 409);
        }

        foreach ($request->items as $item) {
            $expectedAmount = $item['quantity'] * $item['unit_price'];
            $expectedTax = $expectedAmount * ($item['tax_rate'] / 100);

            if (round($expectedTax, 2) != round($item['tax'], 2)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tax amount is not correct.'
                ], 409);
            }
        }  if ($request->cnic && (strlen($request->cnic) != 13 || !is_numeric($request->cnic))) {
                     return response()->json([
                        'success' => false,
                        'message' => 'CNIC is not valid'
                     ],409);
                     }

           if($request->ntn && (strlen($request->ntn) != 7 || !is_numeric($request->ntn))) {
                     return response()->json([
                        'success' => false,
                        'message' => "NTN is not valid"
                     ],409);
           }         
           $irn = 'IRN-' . strtoupper(Str::random(10));
           FbrSubmission::create([
            'invoice_number' => $request->invoice_number,
            'company_id' => $request->business_id,
            'irn' => $irn,
            'status' => 'approved'
           ]);
           return response()->json([
            'success' => true,
            'irn' => $irn,
            'message' => 'Your Invoice has been approvd by fbr'
           ]);
        
    }
}