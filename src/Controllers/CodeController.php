<?php


namespace Gerpo\DmsCredits\Controllers;


use Gerpo\DmsCredits\Jobs\GenerateCode;
use Gerpo\DmsCredits\Models\Code;
use Gerpo\DmsCredits\Rules\CodeExists;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CodeController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'amount' => 'integer',
            'value' => 'required|integer'
        ]);

        $user = $request->user();
        $amount = $request->input('amount', 1);

        GenerateCode::dispatch($user, $data['value'], $amount);
    }

    public function redeem(Request $request): void
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                new CodeExists()
            ]
        ]);

        $user = request()->user();
        $code = Code::active()->where('code', strtoupper($data['code']))->first();

        $user->redeemCode($code);
    }
}