<?php
    session_start();
    const ROOT_DIR = __DIR__;

    if ($_SERVER['HTTP_X_FORWARDED_PROTO'])
        $root = str_replace('http', 'https', ROOT);
    else
        $root = ROOT;

    define('PATH_DIR_CSS', $root . '/multiland/css/');
    define('PATH_DIR_IMG', $root . '/multiland/img/');

    $key = ['category', 'brand', 'product'];
    $value = explode('/', trim($uri['path'], '/'));

    if ( count($value) == 3 ) {
        $path = array_combine($key, $value);
    }
    
    if ( isset($path['category']) ) 
    {
        require ROOT_DIR . '/class/controller/'. $path['category'] .'.php';
        $controllerName = ucfirst($path['category']) . 'Controller';

        if ( isset($path['product']) ) 
        {
            if (!file_exists(ROOT_DIR . '/data/'. $path['category'] .'/'. $path['brand'] .'.php') ) 
                die ('Такой поставщик отсутствует в базе');

            $data = require ROOT_DIR . '/data/'. $path['category'] .'/'. $path['brand'] .'.php';
            $product = $path['product'];
            
            if ( array_key_exists($product, $data))
                define('PATH_DIR_IMG_VENDOR', $root . '/multiland/img/'. $path['category'] .'/'. $path['brand'] .'/');
            else
                define('PATH_DIR_IMG_VENDOR', PATH_DIR_IMG);
        }
        else 
        {
            $data = require ROOT_DIR . '/data/'. $path['category'] .'/default.php';
            $product = 'default';
        }
    } 
    else 
    {
        require ROOT_DIR . '/class/controller/default.php';
        $controllerName = 'DefaultController';

        $data = require ROOT_DIR . '/data/default.php';
        $product = 'default';
        define('PATH_DIR_IMG_VENDOR', PATH_DIR_IMG);
    }
    
    $product = new $controllerName($data, $product);

    if ( preg_match('~utm_~', $uri['query']) )
    {
        parse_str($uri['query'], $query);

        $n = 1;

        foreach ($query as $key => $value) 
        {
            if ( preg_match('~utm_campaign|utm_content|utm_term~', $key) ) 
            {
                
                if ($n == 1)
                    $product->url .= '&sub='. $value;

                else 
                    $product->url .= '&sub'. $n .'='. $value;

                $n++;
            }
        }
    }

    $_SESSION['aff'] = $product->url;
    //d($product);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product->meta_title ?></title>

    <!-- CSS -->
    <link href="<?= PATH_DIR_CSS ?>style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div id="main" class="container">

        <section id="offer" class="row">
            <div class="col-12 col-lg-5 order-1 order-lg-0 img">
            <a href="<?= $product->url ?>">
                <img src="<?= PATH_DIR_IMG_VENDOR . $product->img ?>" alt="<?= $product->meta_title ?>" class="figure-img img-fluid rounded">
            </a>
            </div>
            <div class="col-12 col-lg-7 order-0 order-lg-1 info">
                <?= $product->title ?>
                <a href="<?= $product->url ?>" class="btn btn-danger fs-1" onclick="ym(88890201, 'reachGoal', 'CLICK'); return true;"><?= $product->text_button ?></a>
            </div>
        </section>

        <?php if ( isset($product->products)) : ?>
        <section id="catalog" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" style="padding: 100px 0;">
            <?php foreach ($product->products as $elem_card) : ?>
            <div class="col">
                <div class="card h-100">
                    <a href="<?= $elem_card['url'] ?>">
                        <img src="<?= PATH_DIR_IMG_VENDOR . $elem_card['img'] ?>" class="card-img-top" alt="<?= $elem_card['model'] ?>">
                    </a>
                    
                    <div class="card-body">
                        <div class="card-title">
                            <a href="<?= $elem_card['url'] ?>">
                                <span class="display-6"><?= $elem_card['vendor'] ?></span><br/>
                                <span class="fs-5"><?= $elem_card['model'] ?></span>
                            </a>
                        </div>
                        <p class="card-text"><?= $elem_card['description'] ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="<?= $elem_card['url'] ?>" class="btn btn-danger fs-2" onclick="ym(88890201, 'reachGoal', 'CLICK'); return true;">
                            <?= $elem_card['text_button'] ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </section>
        <?php endif ?>

        <section id="info" class="row">
            <div class="col-12">
                <ul class="row list-unstyled">
                    <li class="col-12 col-lg-2">
                        <span>Более 1000 магазинов</span>
                        <span>Бесплатная доставка в любой удобный для вас магазин сети</span>
                    </li>

                    <li class="col-12 col-lg-2">
                        <span>Экспресс - доставка</span>
                        <span>Доставка в течение 2 часов при заказе от 1000 ₽</span>
                    </li>

                    <li class="col-12 col-lg-2">
                        <span>Широкий ассортимент</span>
                        <span>Эксклюзивные бренды, которые представлены только у нас</span>
                    </li>

                    <li class="col-12 col-lg-2">
                        <span>Специальные предложения</span>
                        <span>Большинство специальных предложений, которые действуют в сети, распространяются и на покупки в интернет-магазине</span>
                    </li>

                    <li class="col-12 col-lg-2">
                        <span>Накопительная карта</span>
                        <span>Скидки по накопительным картам клиента действуют при оплате заказов в интернет-магазине</span>
                    </li>
                </ul>
            </div>
        </section>

        <section id="payment" class="row text-center">
            <div class="col-12">
                <span>Доступные способы оплаты</span>
                <img src="<?= PATH_DIR_IMG ?>payments.png" alt="Способы оплаты" class="img-fluid">
            </div>
        </section>
    </div> <!-- /#main -->

    <footer class="container">
        <div class="text-center">© <?= date('Y') ?><br/>Данный интернет-сайт носит исключительно информационный характер и ни при каких условиях информационные материалы и цены, размещенные на сайте, не являются публичной офертой, определяемой п. статей 435 и 437 ГК РФ. Не является стоматологическим материалом. Перед применением проконсультируйтесь с врачом.
        </div>
    </footer>
    
    <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(88890201, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true
        });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/88890201" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    <!-- /Yandex.Metrika counter -->
</body>
</html>