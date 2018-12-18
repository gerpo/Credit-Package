<?php


namespace Gerpo\DmsCredits\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Charge;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_KEY'));

        Charge::create([
            'amount' => 1000,
            'currency' => 'eur',
            'source' => $request->get('id'),
        ]);
    }
}