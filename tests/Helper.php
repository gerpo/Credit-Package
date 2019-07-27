<?php

use DmsCredits\Tests\User;
use Gerpo\DmsCredits\CodeGenerator;
use Gerpo\DmsCredits\Models\CreditAccount;

function createAccount(array $attributes = [], $user = null)
{
    $user = $user ?: createUser();

    return tap($user->creditAccount)->update($attributes);
}

function createUser()
{
    return User::create();
}

function createCode(int $value = 500, $user = null)
{
    $creator = $user ?? createUser();
    $generator = new CodeGenerator();
    return $generator->generateCode($creator, $value);
}