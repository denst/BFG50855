<?php

// Для генерации роботс
Route::set('robots.txt', 'robots.txt')
        ->defaults(array(
            'controller' => 'etc',
            'action' => 'robots',
        ));

// Для генерации сайтмапа
Route::set('sitemap.xml', 'sitemap.xml')
        ->defaults(array(
            'controller' => 'etc',
            'action' => 'sitemap',
        ));

// Объявления
Route::set('adverts', '<cat>(/<type>(/<id>))', array(
            'cat' => '(kvartiry|komnati|doma|zemli|garaji)',
            'type' => '(prodaja|arenda|'.Arr::ListArray(Arr::Make1Array(ORM::factory('Cities_Area')->order_by('name')->find_all(),'en_name'),'','|').')',
        ))
        ->defaults(array(
            'controller' => 'posts',
            'action' => 'show',
        ));

// Объявления комменческой недвижимости
Route::set('commerce_adverts', '<cat>(/<type>(/<commtype>(/<id>)))', array(
            'cat'       => '(commerce)',
            'commtype'  => '('. Arr::ListArray(Arr::Make1Array(ORM::factory('Adverts_Types_Commerce_Type')->order_by('name')->find_all(),'lat_name'),'','|').'|'.Arr::ListArray(Arr::Make1Array(ORM::factory('Cities_Area')->order_by('name')->find_all(),'en_name'),'','|').')',
            'type'      => '(prodaja|arenda|'.Arr::ListArray(Arr::Make1Array(ORM::factory('Cities_Area')->order_by('name')->find_all(),'en_name'),'','|').')',
        ))
        ->defaults(array(
            'controller' => 'posts',
            'action' => 'show',
        ));

// Статические страницы
Route::set('static', '<page>', array(
            'page' => etc::generatePages()
        ))
        ->defaults(array(
            'controller' => 'page',
            'action' => 'show',
        ));

// Новостные блоги
Route::set('blogs', 'blogs/<id>')
        ->defaults(array(
            'controller' => 'blog',
            'action' => 'show',
        ));

Route::set('default', '(<controller>(/<action>(/<id>(/<stuff>))))', array('stuff' => '.*'))
        ->defaults(array(
            'controller' => 'default',
            'action' => 'index',
        ));
?>
