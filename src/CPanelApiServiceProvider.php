<?php

namespace LCloss\CPanelApi;

use Illuminate\Support\ServiceProvider;

class CPanelApiServiceProvider extends ServiceProvider {
    public function boot() {

    }

    public function register() {
        $this->app->bind('CPanelAPI', function ($app) {
            return new CPanelAPI();
        });
    }
}