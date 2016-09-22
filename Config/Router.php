<?php

use App\Router AS Router;

return [
    'globalPattern' => [
        ':id' => '\d+'
    ],
    'routes' => [
        /*=============== ... ===============*/
        /*------ ... ------*/
        '' => [Router::GET, 'home\Home'],
        'explore' => [Router::GET, 'explore\Explore'],
        'explore/:id' => [Router::GET, 'explore\ExploreId'],
        'contact-us' => [Router::GET, 'page\ContactUs'],
    ]
];

