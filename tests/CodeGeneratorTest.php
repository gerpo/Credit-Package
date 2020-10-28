<?php

use DmsCredits\Tests\TestCase;
use Gerpo\DmsCredits\CodeGenerator;
use Gerpo\DmsCredits\Models\Code;

class CodeGeneratorTest extends TestCase
{
    /** @test */
    public function generator_generates_valid_code(): void
    {
        $user = createUser();
        $value = 200;

        $generator = new CodeGenerator();

        $generator->generateCode($user, $value);

        $this->assertDatabaseHas('codes', [
            'value' => $value,
        ]);

        $this->assertCount(1, Code::all());
    }

    /** @test */
    public function generator_generates_multiple_valid_codes(): void
    {
        $user = createUser();
        $value = 200;
        $amount = 100;

        $generator = new CodeGenerator();

        $generator->generateCodes($user, $value, $amount);

        $this->assertDatabaseHas('codes', [
            'value' => $value,
        ]);

        $this->assertCount($amount, Code::all());
    }

    /**
     * @test
     * @large
     */
    public function generator_generates_hugh_amount_of_valid_codes(): void
    {
        $this->markTestSkipped('Test duration is long. Run only when needed.');

        $user = createUser();
        $value = 200;
        $amount = 10000;

        $generator = new CodeGenerator();

        $generator->generateCodes($user, $value, $amount);

        $this->assertDatabaseHas('codes', [
            'value' => $value,
        ]);

        $this->assertCount($amount, Code::all());
    }
}
