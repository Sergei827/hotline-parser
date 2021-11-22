<?php

namespace Classes;

use \Classes\CategoryParser;


/**
 * класс-парсер
 * категорії товарів
 * сайту hotline.ua 
 */
class HotlineParser
{
    protected $categoryParser;
    protected $categoryPageURL;
    protected $mainURL;
    protected $categoryProductPrefix;
    protected $productsList = [];

    /**
     * конструктор  классу приймає
     * обїект з типом CategoryParser,
     * URL категорії,
     * URL сайту (https://hotline.ua),
     * і префікс катгорії (назва товару розміщеного в данній категорії)
     */ 
    public function __construct(CategoryParser $categoryParser, string $categoryPageURL, string $mainURL, string $categoryProductPrefix = '')
    {
        $this->categoryParser = $categoryParser;
        $this->categoryPageURL = $categoryPageURL;
        $this->mainURL = $mainURL;
        $this->categoryProductPrefix = $categoryProductPrefix;
    }


    /**
     * парсить товари з категорії
     * Має два необовязкових параметри:
     *    - $pageStart - початкова сторінка категорії,
     *    - $pageCount - кылькысть сторінок
     * 
     * За замовчуванням парситься перша сторінка категорії
     * Повертає массив спарсених товарів
     * 
     * @param int $pageStart
     * @param int $pageCount
     * 
     * @return array $this->productsList
     * 
     */
    public function parseHotlineCategoryPages(int $pageStart = 0, int $pageCount = 1)
    {
        // парсинг декількох сторінок категорії
        if($pageCount > 1)
        {
            for($i = 1; $i <= $pageCount; $i++, $pageStart++)
            {
                $pageURL = $this->categoryPageURL.$pageStart;
                $pageProductList = $this->categoryParser->parsCategoryPage($pageURL, $this->categoryProductPrefix, $this->mainURL);
                array_merge($this->productsList[], $pageProductList);
            }

            return $this->productsList;
        }

        // парсинг однієї сторінки категорії (за замовчуванням)
        $pageURL = $this->categoryPageURL.$pageStart;
        $this->productsList = $this->categoryParser->parsCategoryPage($pageURL, $this->categoryProductPrefix, $this->mainURL);

        return $this->productsList;
    }



    /**
     * метод парсить прайси товарів 
     * спарсених до цього методом parseHotlineCategoryPages()
     * 
     * приймає один необовязковий параметр 
     * $productCount - кількість товарів
     * 
     * Повертає массив з назвою товару, посиланням на нього,
     * і прайсом цін на цей товар (або false)
     * 
     * @param int $productCount
     * 
     * @return array $priceList
     * @return bool false
     */
    public function parseHotlineCategoryPrices(int $productCount = 0)
    {
        $priceList = [];

        // за замовчуванням парсяться всі товари
        if(($productCount === 0) && (count($this->productsList) > 0))
        {
            $productCount = count($this->productsList);  
        }
        
        // якщо вказана кількість прайсів перевищу кількість спарсених товарів
        if(($productCount > 0) && (count($this->productsList) < $productCount))
        {
            $productCount = count($this->productsList); 
        }

        // парсинг прайсів товарів категорії
        if(count($this->productsList) > 0 )
        {
            for($i = 0; $i < $productCount; $i++)
            {
                $priceList[$i]['name'] = $this->productsList[$i]['name'];
                $priceList[$i]['link'] = $this->productsList[$i]['link'];
                $priceList[$i]['price'] = $this->categoryParser->parsProductPrice($this->productsList[$i]['link'], $this->mainURL);
            }

            return $priceList;
        }
        else{
            return false;
        }

    }
}