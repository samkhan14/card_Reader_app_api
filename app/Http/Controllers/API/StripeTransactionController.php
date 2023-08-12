<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Charge;

class StripeTransactionController extends Controller
{
    public function fetchTransactions(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Fetch charges (transactions) from Stripe
            $charges = Charge::all();

            return response()->json(['success' => true, 'charges' => $charges]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
