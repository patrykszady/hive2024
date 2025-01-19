<?php

use Illuminate\Support\Facades\Facade;

return [

    'log_max_files' => 180,

    'aliases' => Facade::defaultAliases()->merge([
        'Goutte' => Weidner\Goutte\GoutteFacade::class,
    ])->toArray(),

];
