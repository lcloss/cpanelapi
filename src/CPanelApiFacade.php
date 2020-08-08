<?php

namespace LCloss\CPanelApi;

use Illuminate\Support\Facades\Facade;

class CPanelApiFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'CPanelAPI';
    }
}