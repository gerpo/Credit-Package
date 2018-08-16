<?php


namespace Gerpo\DmsCredits;


use Gerpo\DmsCredits\Projectors\AccountBalanceProjector;
use Gerpo\DmsCredits\Projectors\AccountProjector;
use Illuminate\Support\ServiceProvider;
use Spatie\EventProjector\Projectionist;

class CreditServiceProvider extends ServiceProvider
{
    public function boot(Projectionist $projectionist): void
    {
        $this->publishes([
            __DIR__ . '/config/DmsCredits.php' => config_path('DmsCredits.php'),
        ], 'config');

        $this->exportMigrations();

        $this->loadViewsFrom(__DIR__.'/views', 'DmsCredits');
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');

        $this->registerProjectors($projectionist);
    }

    public function exportMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    private function registerProjectors(Projectionist $projectionist): void
    {
        $projectionist->addProjectors([
            AccountProjector::class,
            AccountBalanceProjector::class,
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/DmsCredits.php', 'DmsCredit'
        );
    }
}