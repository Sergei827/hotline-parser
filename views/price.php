<!DOCTYPE html>

<html>
    <head>
        <title>Ціни на телевізори</title>
        <meta charset='utf-8'>

        <link rel='stylesheet' href='public/css/style.css'>
    </head>
    <body>
          <?php 
              // відображення спарсеної інформації 
              if(!empty($categoryProductsPrices))
              {
                  foreach($categoryProductsPrices as $price)
                  {
                      echo "<div style='margin-bottom: 50px'>";
                      
                      // функція формування таблиці на основі массиву 
                      echo create_price_html_table($price); 
                      echo "</div>";   
                  }
              }
          ?>
    </body>        
</html>    