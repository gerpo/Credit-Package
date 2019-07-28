<?php


namespace Gerpo\DmsCredits\Controllers;


use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Exceptions\CouldNotTransferCredits;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class CreditsTransferController
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'target' => 'required|string|exists:users,username',
            'amount' => 'required|integer'
        ]);

        $userClass = config('auth.providers.users.model');
        $target = (new $userClass())->where('username', $data['target'])->first();

        try {
            $referenceUuid = (string)Uuid::uuid4();
            $request->user()
                ->creditAccount
                ->transferCredits($target->creditAccount->uuid, $data['amount'], $referenceUuid);

            AccountAggregate::retrieve($target->creditAccount->uuid)
                ->receiveCredits($request->user()->creditAccount->uuid, $data['amount'], $referenceUuid)
                ->persist();
        } catch (CouldNotTransferCredits $exception) {
            $error = ValidationException::withMessages([
                'amount' => [$exception->getMessage()],
            ]);
            throw $error;
        }
    }
}