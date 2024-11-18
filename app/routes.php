<?php

use SPF\Routing\Router;
use SPF\View\View;

Router::get('/', 'HomeController@index');

Router::get('/about', function () {
   return 'About Us';
});

Router::get('/spf', function () {
   return new View('index.html', ['framework' => 'SPF']);
});
