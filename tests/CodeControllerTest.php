<?php


use DmsCredits\Tests\TestCase;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Jobs\GenerateCode;

class CodeControllerTest extends TestCase
{
    /** @test */
    public function code_is_successfully_created(): void
    {
        $user = createUser();
        $this->signInAdmin($user);

        $this->post(route('credits.code.create'), ['value' => 200])
            ->assertSuccessful();

        Bus::assertDispatched(GenerateCode::class, function ($job) use ($user) {
            return ($job->value === 200 && $job->amount === 1 && $job->user === $user);
        });
    }

    /** @test */
    public function multiple_codes_are_successfully_created(): void
    {
        $user = createUser();
        $this->signInAdmin($user);

        $this->post(route('credits.code.create'), ['value' => 200, 'amount' => 20])
            ->assertSuccessful();

        Bus::assertDispatched(GenerateCode::class, function ($job) use ($user) {
            return ($job->value === 200 && $job->amount === 20 && $job->user === $user);
        });
    }

    /** @test */
    public function admin_can_create_code(): void
    {
        $user = createUser();

        $this->signInAdmin($user)
            ->post(route('credits.code.create'), ['value' => 200])
            ->assertSuccessful();

        Bus::assertDispatched(GenerateCode::class, function ($job) use ($user) {
            return ($job->value === 200 && $job->amount === 1 && $job->user === $user);
        });
    }

    /** @test */
    public function authorized_user_can_create_code(): void
    {
        $user = createUser();

        $this->signIn($user, 'create_codes')
            ->post(route('credits.code.create'), ['value' => 200])
            ->assertSuccessful();

        Bus::assertDispatched(GenerateCode::class, function ($job) use ($user) {
            return ($job->value === 200 && $job->amount === 1 && $job->user === $user);
        });
    }

    /** @test */
    public function unauthorized_user_cannot_create_code(): void
    {
        $this->signIn()
            ->post(route('credits.code.create'), ['value' => 200])
            ->assertStatus(403);

        Bus::assertNotDispatched(GenerateCode::class);
    }

    /** @test */
    public function guest_cannot_create_code(): void
    {
        $this->post(route('credits.code.create'), ['value' => 200])
            ->assertStatus(500);

        Bus::assertNotDispatched(GenerateCode::class);
    }

    /** @test */
    public function valid_code_can_be_redeemed(): void
    {
        $user = createUser();
        $code = createCode();

        $this->actingAs($user);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);
    }

    /** @test */
    public function valid_code_can_be_entered_case_insensitive(): void
    {
        $user = createUser();
        $code = createCode();

        $this->actingAs($user);

        $lowerCode = strtolower($code->code);
        $this->post(route('credits.code.redeem'), ['code' => $lowerCode])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);
    }

    /** @test */
    public function invalid_code_cannot_be_redeemed(): void
    {
        $user = createUser();
        $code = createCode();

        $this->actingAs($user);

        Event::fake([CreditsAdded::class]);

        $this->post(route('credits.code.redeem'), ['code' => 'abcdef'])
            ->assertStatus(302);

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => null,
        ]);

        $this->assertDatabaseMissing('codes', [
            'code' => 'abcdef',
            'used_by' => $user->id,
        ]);

        Event::assertNotDispatched(CreditsAdded::class, function ($event) use ($user) {
            return $event->accountUuid === $user->fresh()->creditAccount->uuid;
        });
    }

    /** @test */
    public function used_code_cannot_be_redeemed_again_by_other_user(): void
    {
        $user = createUser();
        $user2 = createUser();
        $this->actingAs($user);

        $value = 200;
        $code = createCode($value);

        Event::fake([CreditsAdded::class]);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);

        Event::assertDispatched(CreditsAdded::class, function ($event) use ($user, $value) {
            return $event->accountUuid === $user->fresh()->creditAccount->uuid && $event->amount === $value;
        });

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(302);

        $this->assertDatabaseMissing('codes', [
            'code' => $code->code,
            'used_by' => $user2->id,
        ]);

        Event::assertNotDispatched(CreditsAdded::class, function ($event) use ($user2) {
            return $event->accountUuid === $user2->fresh()->creditAccount->uuid;
        });
    }

    /** @test */
    public function used_code_cannot_be_redeemed_again_by_same_user(): void
    {
        $user = createUser();
        $this->actingAs($user);

        $value = 200;
        $code = createCode($value);

        Event::fake([CreditsAdded::class]);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);


        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(302);

        Event::assertDispatched(CreditsAdded::class, 1);
        Event::assertDispatched(CreditsAdded::class, function ($event) use ($user, $value) {
            return $event->accountUuid === $user->fresh()->creditAccount->uuid && $event->amount === $value;
        });
    }

    /** @test */
    public function guest_cannot_redeem_code(): void
    {
        $code = createCode();

        Event::fake([CreditsAdded::class]);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(500);

        Event::assertNotDispatched(CreditsAdded::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();
    }
}