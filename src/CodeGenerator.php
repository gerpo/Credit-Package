<?php


namespace Gerpo\DmsCredits;


use Gerpo\DmsCredits\Models\Code;
use Illuminate\Foundation\Auth\User;

class CodeGenerator
{
    /*
     * @var Collection
     */
    private $currentCodes;

    public function __construct()
    {
        $this->currentCodes = Code::all('code');
    }

    public function generateCodes(User $creator, int $value, int $amount = 1)
    {
        $codes = collect();
        for ($i = 0; $i < $amount; $i++) {
            $codes->push($this->generateCode($creator, $value));
        }

        return $codes;
    }

    public function generateCode(User $creator, int $value): Code
    {
        $code = Code::make([
            'value' => $value,
            'code' => $this->generateUniqueCode()
        ]);

        while (!$creator->createdCodes()->save($code)) {
            $code->code = $this->generateUniqueCode();
        }

        return $code;
    }

    /**
     * @param int $length
     * @return String
     * @throws \Exception
     */
    public function generateUniqueCode(int $length = null): String
    {
        $length = $length ?? config('DmsCredit.code_length', 8);
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nChars = strlen($chars) - 1;
        $res = str_repeat(0, $length);
        for ($i = 0; $i < $length; $i++) {
            $res[$i] = $chars[random_int(0, $nChars)];
        }

        if ($this->currentCodes->contains('code', $res)) {
            return $this->generateUniqueCode($length);
        }

        $this->currentCodes->push($res);
        return $res;
    }


}