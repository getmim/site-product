<?php

return [
    '__name' => 'site-product',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-product.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/site-product' => ['install','update','remove'],
        'app/site-product' => ['install','remove'],
        'theme/site/product' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'product' => NULL
            ],
            [
                'site' => NULL
            ],
            [
                'site-meta' => NULL
            ],
            [
                'lib-formatter' => NULL
            ],
            [
                'lib-upload' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-event' => NULL
            ],
            [
                'lib-cache-output' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'SiteProduct\\Controller' => [
                'type' => 'file',
                'base' => ['modules/site-product/controller','app/site-product/controller']
            ],
            'SiteProduct\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-product/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteProductSingle' => [
                'path' => [
                    'value' => '/product/read/(:slug)',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SiteProduct\\Controller\\Product::single'
            ],
            'siteProductFeed' => [
                'path' => [
                    'value' => '/product/feed.xml'
                ],
                'method' => 'GET',
                'handler' => 'SiteProduct\\Controller\\Robot::feed'
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'product' => [
                'page' => [
                    'type' => 'router',
                    'router' => [
                        'name' => 'siteProductSingle',
                        'params' => [
                            'slug' => '$slug'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libEvent' => [
        'events' => [
            'product:created' => [
                'SiteProduct\\Library\\Event::clear' => TRUE
            ],
            'product:deleted' => [
                'SiteProduct\\Library\\Event::clear' => TRUE
            ],
            'product:updated' => [
                'SiteProduct\\Library\\Event::clear' => TRUE
            ]
        ]
    ],
    'site' => [
        'robot' => [
            'feed' => [
                'SiteProduct\\Library\\Robot::feed' => TRUE
            ],
            'sitemap' => [
                'SiteProduct\\Library\\Robot::sitemap' => TRUE
            ]
        ]
    ]
];