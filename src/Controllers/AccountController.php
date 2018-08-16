<?php


namespace Gerpo\DmsCredits\Controllers;

use Illuminate\Routing\Controller;

class AccountController extends Controller
{
    public function index()
    {
        return view('DmsCredits.credits');
    }
}