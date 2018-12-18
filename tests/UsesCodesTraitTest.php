<?php


use DmsCredits\Tests\TestCase;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Illuminate\Database\Eloquent\Model;

class UsesCodesTraitTest extends TestCase
{
    private $initialDispatcher;

    /** @test */
    public function assert_correct_credit_amount_is_added_when_code_is_redeemed(): void
    {
        $user = createUser();
        $value = 250;
        $code = createCode($value);

        $this->actingAs($user);

        $this->withoutExceptionHandling()
            ->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        Event::assertDispatched(CreditsAdded::class, function ($event) use ($value) {
            return $event->amount === $value;
        });
    }

    /** @test */
    public function used_by_is_correctly_set_to_user_that_redeemed_code(): void
    {
        $user = createUser();
        $value = 250;
        $code = createCode($value);

        $this->actingAs($user);

        $this->assertNull($code->fresh()->user);

        $this->withoutExceptionHandling()
            ->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertEquals($user->id, $code->fresh()->user->id);
    }

    /** @test */
    public function creator_id_is_set_to_correct_user(): void
    {
        $user = createUser();
        $value = 200;
        $this->signInAdmin($user);

        $this->post(route('credits.code.create'), ['value' => $value])
            ->assertSuccessful();

        $this->assertDatabaseHas('codes', [
            'value' => $value,
            'created_by' => $user->id
        ]);
    }

    /** @test */
    public function used_at_timestamp_is_set_when_code_is_used(): void
    {
        Model::setEventDispatcher($this->initialDispatcher);

        $user = createUser();
        $code = createCode();

        $this->actingAs($user);

        $this->assertNull($code->fresh()->used_at);

        $this->withoutExceptionHandling()
            ->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        $this->assertNotNull($code->fresh()->used_at);
    }

    /** @test */
    public function correct_event_massage_is_provided(): void
    {

        $user = createUser();
        $value = 250;
        $code = createCode($value);

        $this->actingAs($user);

        $this->withoutExceptionHandling()
            ->post(route('credits.code.redeem'), ['code' => $code->code])
            ->assertSuccessful();

        Event::assertDispatched(CreditsAdded::class, function ($event) use($value) {
            return $event->message === 'DmsCredits::code.redeem_message';
        });
    }

    protected function setUp()
    {
        parent::setUp();

        $this->initialDispatcher = Event::getFacadeRoot();
        Event::fake([
            CreditsAdded::class,
        ]);
    }
}