<?php

// конфігурація парсера

// посилання на сайт
define('MAIN_URL', 'https://hotline.ua');
// посилання на бібліотеку phpQuery
define('PHP_QUERY_LINK', 'libs/phpQuery/phpQuery-onefile.php');

// массив з посиланнями на категорії
$hotlineCategories = [
    'tv' => [
                'link' => 'https://hotline.ua/av/televizory/?p=',
                'prefix' => 'Телевізор',
            ],       
];


// селектори для пошуку елементів сторінок
$selectorsList = [
    'products_list' => '.products-list',
    'product_item' => '.product-item',
    'product_link' => '.product-item .item-info .h4 a',
    'price_list' => '.list',
    'price_items' => '.list__item',
    'shop' => '.shop__title',
    'price' => '.info__price a span .price__value',

]; 