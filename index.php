<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-type: text/html; chatset=utf-8');

// підключееня конфігурації
require_once "config/parser_config.php";
// підключення функції генерації таблиці
require_once "libs/create_price_html_table.php";

// автозавантаження классів
spl_autoload_register(function($class){
    $path = $class;
    $path = str_replace('\\', '/', $path);

    include_once $path.'.php';
});

// інстанціювання классу-парсера CategoryParser
$categoryParser = new \Classes\CategoryParser(PHP_QUERY_LINK, $selectorsList);
// інстанціювання классу-парсера категорії товару з сайту hotline.ua
$hotlineParser = new \Classes\HotlineParser($categoryParser, $hotlineCategories['tv']['link'], MAIN_URL, $hotlineCategories['tv']['prefix']);

// парсинг сторінки категорії hotline.ua
$hotlineParser->parseHotlineCategoryPages();
// парсинг прайсів для 5 товарів з заданної категорії
$categoryProductsPrices = $hotlineParser->parseHotlineCategoryPrices(5);

// сторінка відображення спарсеної інформації 
include_once "views/price.php";



