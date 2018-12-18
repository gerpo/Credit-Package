<?php


namespace Gerpo\DmsCredits\Controllers;

use Illuminate\Routing\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $account = auth()->user()->creditAccount->append('transactions');
        return view('DmsCredits::credits')->with('account', $account);
    }
}