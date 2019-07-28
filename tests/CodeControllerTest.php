<?php


use DmsCredits\Tests\TestCase;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Jobs\GenerateCode;
use Illuminate\Support\Arr;

class CodeControllerTest extends TestCase
{
    /** @test */
    public function index_returns_all_active_codes_of_user(): void
    {
        $user = createUser();
        $user2 = createUser();
        $code = createCode(500, $user);
        $code2 = createCode(500, $user);

        createCode(500, $user2);

        $user2->redeemCode($code2);

        $this->signInAdmin($user)
            ->get(route('credits.code.index'))
            ->assertSuccessful()
            ->assertExactJson([$code->fresh()->toArray()]);
    }

    /** @test */
    public function code_is_successfully_created(): void
    {
        Bus::fake();

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
        Bus::fake();

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
        Bus::fake();

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
        Bus::fake();

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
        Bus::fake();

        $this->signIn()
            ->post(route('credits.code.create'), ['value' => 200])
            ->assertStatus(403);

        Bus::assertNotDispatched(GenerateCode::class);
    }

    /** @test */
    public function guest_cannot_create_code(): void
    {
        Bus::fake();

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

        $this->assertEquals(500, $user->creditAccount->fresh()->balance);
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

        $this->assertEquals(500, $user->creditAccount->fresh()->balance);
    }

    /** @test */
    public function invalid_code_cannot_be_redeemed(): void
    {
        $user = createUser();
        $code = createCode();

        $this->actingAs($user);

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

        $this->assertEquals(0, $user->creditAccount->fresh()->balance);
    }

    /** @test */
    public function used_code_cannot_be_redeemed_again_by_other_user(): void
    {
        $user = createUser();
        $user2 = createUser();
        $this->actingAs($user);

        $value = 200;
        $code = createCode($value);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);
        $this->assertEquals(200, $user->creditAccount->fresh()->balance);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(302);

        $this->assertDatabaseMissing('codes', [
            'code' => $code->code,
            'used_by' => $user2->id,
        ]);

        $this->assertEquals(0, $user2->creditAccount->fresh()->balance);
    }

    /** @test */
    public function used_code_cannot_be_redeemed_again_by_same_user(): void
    {
        $user = createUser();
        $this->actingAs($user);

        $value = 200;
        $code = createCode($value);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => $user->id,
        ]);

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(302);

        $this->assertEquals(200, $user->creditAccount->fresh()->balance);
    }

    /** @test */
    public function guest_cannot_redeem_code(): void
    {
        $code = createCode();

        $this->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertStatus(500);

        $this->assertDatabaseHas('codes', [
            'code' => $code->code,
            'used_by' => null
        ]);
    }
}