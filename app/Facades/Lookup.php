<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Lookup extends Facade {
    protected static function getFacadeAccessor() { return 'lookup'; }
}