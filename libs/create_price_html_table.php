<?php


/**
 * функція приймає массив спарсеного товару з його прайсом
 * і повертає створену на його основі html таблицю
 * 
 * @param array $productArr
 * 
 * @return string $tableProductPriceStr
 */
function create_price_html_table($productArr)
{
    
    $tableProductPriceStr = "<table class='price-table'>";
    $tableProductPriceStr .= "<tr>";
    $tableProductPriceStr .= "<th colspan='2' class='price-table-ceil'>";
    $tableProductPriceStr .= "<a href='{$productArr['link']}'>";
    $tableProductPriceStr .= $productArr['name'];
    $tableProductPriceStr .= "</a>";
    $tableProductPriceStr .= "</th>";
    $tableProductPriceStr .= "<th></th>";
    $tableProductPriceStr .= "</tr>";

    $tableProductPriceStr .= "<tr>";
    $tableProductPriceStr .= "<th class='price-table-ceil'>Магазин</th>";
    $tableProductPriceStr .= "<th class='price-table-ceil'>Ціна</th>";
    $tableProductPriceStr .= "</tr>";

    $priceCount = count($productArr['price']);

    if($priceCount > 0)
    {
        for($i = 0; $i < $priceCount; $i++)
        {
            $tableProductPriceStr .= "<tr>";
            $tableProductPriceStr .= "<td class='price-table-ceil'>";
            $tableProductPriceStr .= "<a href='{$productArr['price'][$i]['shop-link']}'>";
            $tableProductPriceStr .= $productArr['price'][$i]['shop'];
            $tableProductPriceStr .= "</a>";
            $tableProductPriceStr .= "</td>";

            $tableProductPriceStr .= "<td class='price-table-ceil'>";
            $tableProductPriceStr .= $productArr['price'][$i]['price'];
            $tableProductPriceStr .= "</td>";
            $tableProductPriceStr .= "</tr>";
        }
    }

    $tableProductPriceStr .= '</table>';
     
    return $tableProductPriceStr;
}