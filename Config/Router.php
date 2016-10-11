<?php

use App\Router AS Router;

return [
    'globalPattern' => [
        ':id' => '\d+',
        ':mapping' => '[A-Za-z0-9\-\_\/]+',
        ':path' => '[A-Za-z0-9\-\_]+'
    ],
    'routes' => [
        /*=============== Mapper ===============*/
        // API mapper
        'mapper/:mapping' => [Router::ALL, 'mapper\Request'],

        /*=============== ... ===============*/
        /*------ ... ------*/
        '' => [Router::GET, 'home\Home'],
        'explore' => [Router::GET, 'explore\Explore'],
        'explore/:id' => [Router::GET, 'explore\ExploreId'],
        'contact-us' => [Router::GET, 'page\ContactUs'],
    ]
];

