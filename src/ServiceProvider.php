<?php

namespace Phpsa\Datastore;

use Illuminate\Support\Facades\Blade;
use Phpsa\Datastore\Helpers;
use Illuminate\Support\Facades\Route;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/datastore.php';

    public function boot()
    {
		// publish our pacakge
        $this->publishes([
            self::CONFIG_PATH => config_path('datastore.php'),
		], 'config');

		//set our migratinos directory
		$this->loadMigrationsFrom(__DIR__.'/Database/migrations');

		//Router
		Route::middleware('web')
			->group(__DIR__.'/routes.php');

		//Breadcrumbs
		if (class_exists('Breadcrumbs')) {
			require __DIR__ . '/breadcrumbs.php';
		}

		//Translations
		$this->loadTranslationsFrom(__DIR__.'/translations', 'phpsa-datastore');

		$this->publishes([
			__DIR__.'/translations' => resource_path('lang/vendor/phpsa-datastore'),
			// Assets
			__DIR__.'/resources/js' => public_path('vendor/phpsa-datastore/js'),
			__DIR__.'/resources/css' => public_path('vendor/phpsa-datastore/css'),
		]);

		$this->registerBladeHelpers();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'datastore'
        );

        $this->app->bind('datastore', function () {
            return new Datastore();
		});

		$this->loadViewsFrom(__DIR__.'/views', 'phpsa-datastore');
	}


	public function registerBladeHelpers(){
		Blade::directive('datastoreAssetList', function ($grouped = false) {
            return Helpers::getAssetList($grouped, false);
		});

		Blade::directive('forDatastores', function () {
			return "<?php foreach(Phpsa\Datastore\Helpers::getAssetList(1, 0) as \$datastoreKey => \$datastoreData): ?>";
		});

		Blade::directive('endforDatastores', function () {
			return "<?php endforeach; ?>";
		});
		Blade::directive('datastoreFieldName', function ($data) {
			return 'hahaha';
		});
	}
}
