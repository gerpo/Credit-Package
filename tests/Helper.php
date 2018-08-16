<?php

use DmsCredits\Tests\User;
use Gerpo\DmsCredits\Models\CreditAccount;

function createAccount(array $attributes = [], $user = null)
{
    $user = $user ?: createUser();

    $attributes = array_merge([
        'owner_id' => $user->id,
        'owner_type' => User::class
    ], $attributes);

    return CreditAccount::createWithAttributes($attributes);
}

function createUser()
{
    return User::create();
}