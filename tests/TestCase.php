<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\CreditServiceProvider;
use Gerpo\DmsCredits\Traits\HasCreditAccount;
use Gerpo\DmsCredits\Traits\UsesCodes;
use Illuminate\Support\Facades\Schema;
use Silber\Bouncer\BouncerServiceProvider;
use Silber\Bouncer\Database\HasRolesAndAbilities;
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
            BouncerServiceProvider::class,
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

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', \DmsCredits\Tests\TestHttpKernel::class);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->removeOldMigrations();

        $this->artisan('vendor:publish',
            ['--provider' => 'Spatie\EventProjector\EventProjectorServiceProvider',]);

        $this->artisan('vendor:publish',
            ['--provider' => BouncerServiceProvider::class,]);

        $this->migrate();
    }

    private function removeOldMigrations(): void
    {
        $files = glob(realpath(__DIR__ . '/../vendor/orchestra/testbench-core/laravel/database/migrations') . '/*.php');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
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

    protected function signIn($user = null, $ability = null)
    {
        $user = $user ?: createUser();

        $user->allow($ability);

        $this->actingAs($user);

        return $this;
    }

    protected function signInAdmin($admin = null)
    {
        $admin = $admin ?: createUser();

        $admin->assign('admin');

        $this->actingAs($admin);

        return $this;
    }
}

class User extends \Illuminate\Foundation\Auth\User
{
    use HasCreditAccount;
    use UsesCodes;
    use HasRolesAndAbilities;
    protected $table = 'users';
    protected $guarded = [];
}
