<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'log_max_files' => 180,


    'aliases' => Facade::defaultAliases()->merge([
        'Goutte' => Weidner\Goutte\GoutteFacade::class,
    ])->toArray(),

];
