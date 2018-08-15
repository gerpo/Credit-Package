<?php

namespace Gerpo\DmsCredits\Test;

use Gerpo\DmsCredits\CreditServiceProvider;
use HasCreditAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            CreditServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    private function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testing']);

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
}

class User extends Model
{
    use HasCreditAccount;
    protected $table = 'users';
    protected $guarded = [];
}
