<?php

return [
    'name' => 'Dependency',
    'nameCn' => '依赖项',
    'default' => [
        'cdn' => 'unpkg',
    ],

    'cdns' => [
        "unpkg" => [
            "host" => "https://unpkg.com/",
        ],
        "jsdelivr" => [
            'host' => 'https://cdn.jsdelivr.net/npm/',
        ],
        "cdnjs" => [
            "host" => "https://cdnjs.cloudflare.com/",

        ],
    ],

];
