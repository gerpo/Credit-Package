<?php

use DmsCredits\Tests\User;
use Gerpo\DmsCredits\CodeGenerator;

function createAccount(array $attributes = [], $user = null)
{
    $user = $user ?: createUser();

    return tap($user->creditAccount)->update($attributes);
}

function createUser()
{
    return tap(User::create(), function ($user) {
        $user->allow('have_credits');
    });
}

function createCode(int $value = 500, $user = null)
{
    $creator = $user ?? createUser();
    $generator = new CodeGenerator();

    return $generator->generateCode($creator, $value);
}
