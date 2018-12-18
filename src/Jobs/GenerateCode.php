<?php

namespace Gerpo\DmsCredits\Jobs;

use Gerpo\DmsCredits\CodeGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $value;
    /**
     * @var int
     */
    public $amount;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param int $value
     * @param int $amount
     */
    public function __construct(User $user, int $value, int $amount = 1)
    {
        $this->user = $user;
        $this->value = $value;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @param CodeGenerator $generator
     * @return void
     */
    public function handle(CodeGenerator $generator): void
    {
        $generator->generateCodes($this->user, $this->value, $this->amount);
    }
}
