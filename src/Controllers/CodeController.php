<?php


namespace Gerpo\DmsCredits\Controllers;


use Gerpo\DmsCredits\Jobs\GenerateCode;
use Gerpo\DmsCredits\Models\Code;
use Gerpo\DmsCredits\Rules\CodeExists;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDF;

class CodeController extends Controller
{
    public function index()
    {
        return request()->user()->createdCodes()->active()->get();
    }

    public function export()
    {
        $codes = request()->user()->createdCodes()->notExported()->get();

        $codes->each(function ($code) {
            $code->export();
        });

        return PDF::loadView('DmsCredits::codesPdfTemplate', ['codes' => $codes])
            ->setPaper('a4', 'portrait')
            ->stream();
    }

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