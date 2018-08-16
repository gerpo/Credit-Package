<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\CreditServiceProvider;
use Gerpo\DmsCredits\Traits\HasCreditAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Spatie\EventProjector\EventProjectorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
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
            EventProjectorServiceProvider::class,
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

    protected function setUp()
    {
        parent::setUp();
        $this->removeOldMigrations();

        $this->artisan('vendor:publish',
            ['--provider' => 'Spatie\EventProjector\EventProjectorServiceProvider',]);

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

    protected function tearDown()
    {
        $this->removeOldMigrations();
    }

    private function removeOldMigrations(): void
    {
        $files = glob(realpath(__DIR__ . '/../vendor/orchestra/testbench-core/laravel/database/migrations').'/*.php');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

class User extends Model
{
    use HasCreditAccount;
    protected $table = 'users';
    protected $guarded = [];
}
