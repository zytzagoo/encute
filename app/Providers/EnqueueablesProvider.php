<?php

namespace CWS\Encute\Providers;

use CWS\Encute\Illuminate\Support\ServiceProvider;

class EnqueueablesProvider extends ServiceProvider {
	public function register() {
		$this->app->singleton(\WP_Scripts::class, function () {
			return wp_scripts();
		});
		$this->app->singleton(\WP_Styles::class, function () {
			return wp_styles();
		});
	}
}
