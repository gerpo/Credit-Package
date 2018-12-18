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
            __DIR__ . '/Config/DmsCredits.php' => config_path('DmsCredits.php'),
        ], 'config');


        $this->exportMigrations();

        $this->exportResources();
        $this->loadRoutesFrom(__DIR__ . '/Routes/routes.php');

        $this->registerProjectors($projectionist);
    }

    public function exportMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    public function exportResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'DmsCredits');
        $this->loadTranslationsFrom(__DIR__.'/Resources/Lang', 'DmsCredits');

        $this->publishes([
            __DIR__ . '/Resources/Vue-Components' =>
                resource_path('assets/js/gerpo/DmsCredits'
                )
        ], 'vue-components');

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
            __DIR__ . '/Config/DmsCredits.php', 'DmsCredit'
        );
    }
}