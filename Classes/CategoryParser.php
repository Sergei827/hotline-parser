<?php

namespace Classes;



/**
 * класс парсингу сторінки 
 * товарів певної категорії 
 * на сайті hotline.ua
 */

class CategoryParser
{
    protected $phpQueryLibLink;
    protected $selectorsList = [];
    protected $productLinks = [];
    
    public function __construct(string $phpQueryLibLink, array $selectorsList)
    {
        if(!empty($phpQueryLibLink)){
            $this->phpQueryLibLink = $phpQueryLibLink;
        }
        
        if(count($selectorsList) > 0){
            $this->selectorsList = $selectorsList;
        }
    }

    /**
     * метод парсить одну сторінку 
     * категорії hotline.ua
     * 
     * Приймає URL сторінки, 
     * назву типу товару (нариклад "Телевізори"),
     * і URL самого сайту для формування з отриманих
     * відносніх посилань повних посилань на товари (https://hotline.ua).
     * 
     * Повертає двомірний массив з назвами товарів і посиланнями
     * на їх прайс, або false
     * 
     * @param string $categoryPageURL
     * @param string $categoryProductPrefix
     * @param string $mainLink
     * 
     * @return array $this->productLinks
     * @return bool false
     */
    public function parsCategoryPage(string $categoryPageURL, string $categoryProductPrefix, string $mainLink)
    {
        // підкючення бібліотеки phpQuery
        require_once $this->phpQueryLibLink;;

        if(class_exists('phpQuery'))
        {
            // отримання сторінки з товаром
            $doc = file_get_contents($categoryPageURL);
            $this->categoriesObj = \phpQuery::newDocument($doc);

            // парсинг товарів на сторінці
            $productLinks = $this->categoriesObj->find($this->selectorsList['product_link']);
        
            $i=0;
            foreach($productLinks as $link)
            {
                $link = pq($link);
                $this->productLinks[$i]['name'] = $categoryProductPrefix.$link->html();
                $this->productLinks[$i]['link'] = $mainLink.$link->attr('href');

                $i++;
            }

            return $this->productLinks;
        }
        else{
            return false;
        }
    }



    /**
     * метод отримує посилання на прайс товару
     * на сайті (https://hotline.ua)
     * і парсить назви магазинів, посилання на 
     * них і актуальні ціни
     * 
     * Приймає посилання на прайс товару і 
     * URL самого сайту для формування з отриманих
     * відносніх посилань повних посилань на товари (https://hotline.ua)
     * 
     * Повертає массив з назвою магазину, ціною і посиланням
     * 
     * @param string $productLink;
     * @param string $mainLink
     * 
     * @return array $price
     * @return bool false
     */

    public function parsProductPrice(string $productLink, string $mainLink)
    {
        // підкючення бібліотеки phpQuery
        require_once $this->phpQueryLibLink;

        if(class_exists('phpQuery'))
        {
            // отримання сторінки з прайсом товару
            $doc = file_get_contents($productLink);
            $doc = \phpQuery::newDocument($doc);

            // парсинг прайсу товару
            $priceList = $doc->find($this->selectorsList['price_list']);
            $priceList = $priceList->find($this->selectorsList['price_items']);

            $price = [];

            $i = 0;
            foreach($priceList as $item)
            {
                $item = pq($item);
                $shop = $item->find($this->selectorsList['shop']);
                $shopLink = $shop->attr('href');
                $shopLink = $mainLink.$shopLink;
                $shopPrice = $item->find($this->selectorsList['price']);
                
                

                $price[$i]['shop'] = iconv('utf-8', "ISO-8859-1", $shop->text());
                $price[$i]['shop'] = mb_convert_encoding($price[$i]['shop'] , 'utf-8', mb_detect_encoding($price[$i]['shop']));

                $price[$i]['price'] = str_replace("Â", ' ', $shopPrice->text());
                $price[$i]['shop-link'] = $shopLink;

                $i++;
            }    
            
            return $price;
       }
       else{
           return false;
       }
   }

}   