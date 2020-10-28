<?php

namespace Gerpo\DmsCredits;

use Exception;
use Gerpo\DmsCredits\Models\Code;
use Illuminate\Foundation\Auth\User;

class CodeGenerator
{
    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $currentCodes;

    /**
     * @var int
     */
    private $codeLength;

    /**
     * @var string
     */
    private $codeChars;

    public function __construct()
    {
        $this->currentCodes = Code::all('code');

        $this->codeLength = config('DmsCredit.code_length', 8);
        $this->codeChars = config('DmsCredit.code_chars', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
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
            'code'  => $this->generateUniqueCode(),
        ]);

        while (!$creator->createdCodes()->save($code)) {
            $code->code = $this->generateUniqueCode();
        }

        return $code;
    }

    /**
     * @param int $length
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateUniqueCode(int $length = null): string
    {
        $length = $length ?? $this->codeLength;
        $chars = $this->codeChars;
        $nChars = strlen($chars) - 1;

        $res = str_repeat(0, $length);

        for ($i = 0; $i < $length; $i++) {
            $res[$i] = $chars[random_int(0, $nChars)];
        }

        if (!$this->isNewCode($res)) {
            return $this->generateUniqueCode($length);
        }
        $this->currentCodes->push($res);

        return $res;
    }

    private function isNewCode(string $code): bool
    {
        return !$this->currentCodes->contains('code', $code);
    }
}
