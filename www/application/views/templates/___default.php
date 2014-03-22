	<?php /*
        $num=mt_rand(2000,10000);
        $LastModified=gmdate("D, d M Y H:i:s \G\M\T", time());
        $IfModifiedSince = false;
    	    if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
        $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
       		 if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
        $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
       		 if ($IfModifiedSince && $IfModifiedSince >= $num) 		{
        header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
      		  exit;
      															 	 }
      				  header('Last-Modified: '. $LastModified);*/
        ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtmll" xml:lang="ru" lang="ru">
    <head>
        <title><?= ($_seo->loaded()) ? $_seo->title : $title ?> | <?= APPLICATION_TITLE ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?=trim(strip_tags(($_seo->loaded()) ? $_seo->desc : Text::limit_chars(Text::desc($desc), 160, '...'))) ?>" />
        <meta name="keywords" content="<?=trim(strip_tags(($_seo->loaded()) ? $_seo->keywords : $keywords))?>" />
        <?
            if (!empty($location->city))
                $code = $location->city->code;
            elseif (!empty($location->region))
                $code = $location->region->code;
            else
                $code = $location->country->code;
        ?>
        <meta name="yandex-verification" content="<?= $code ?>" />
        <?= $header ?>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button"class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="/about">О сервисе</a></li>
                            <li class="divider-vertical"></li>
                            <li><a href="/help">Помощь</a></li>
                            <li class="divider-vertical"></li>
                            <li class=""><a href="/news">Новости</a></li>
                            <li class="divider-vertical"></li>
                            <li class=""><a href="/rekl">Реклама на сайте</a></li>
                            <li class="divider-vertical"></li>
                            <li class=""><a href="/contacts">Обратная связь</a></li>
                        </ul>
                        <ul class="nav right">
                            <li><a onclick="showcitiescontainer();return false;" href="#"><?=$location->name?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container logo"><!-----            logo            ----->
            <a href="/" id="logo"></a>
            <a href="/posts/save" class="btn btn-warning"><i></i> Добавить объявление</a>
            <div class="clear"></div>
        </div>
        <div class="navbar menu"><!-----            menu            ----->
            <div class="navbar-inner">
                <div class="container">
                    <button type="button"class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="nav-collapse collapse">
                        <ul id="headmenu" class="nav">
                            <li id="head-flats"><a href="/<?=ADVERTS_CATS_FLAT_STR?>">Квартиры</a></li>
                            <li class=""><a href="/<?=ADVERTS_CATS_ROOM_STR?>">Комнаты</a></li>
                            <li id="head-houses">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Котеджи</a>
                                <ul class="dropdown-menu">
                                    <li><a onclick="$(this).parent().parent().parent().removeClass('active').removeClass('open'); Navigation.loadPage('/<?=ADVERTS_CATS_HOUSE_STR?>');" href="/<?=ADVERTS_CATS_HOUSE_STR?>">Дома и котеджи</a></li>
                                    <li><a onclick="$(this).parent().parent().parent().removeClass('active').removeClass('open'); Navigation.loadPage('/<?=ADVERTS_CATS_TERRAIN_STR?>');" href="/<?=ADVERTS_CATS_TERRAIN_STR?>">Земельные участки</a></li>
                                </ul>
                            </li>
                            <li id="head-garages"><a href="/<?=ADVERTS_CATS_GARAGE_STR?>">Гаражи</a></li>
                            <li id="head-garages"><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>">Коммерческая</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container main"><!-----            main            ----->
            <div class="row-fluid" id="content">
                <?=$content?>
            </div>
        </div>

        <div class="navbar navbar-fixed-bottom foot"><!-----          foot            ----->
            <div class="navbar-inner">
                <div class="container">
                    <? if (Request::current()->uri() == '/') { ?>
                    <ul class="nav">
                        <li><a>Недвижимость:</a></li>
                        <? $countries = ORM::factory('country')->find_all(); ?>
                        <? foreach($countries as $country) if ($country->id !== $location->country->id) { ?>
                            <li class="divider-vertical"></li>
                            <li>
                                <a target="_blank" href="http://realtynova.<?=$country->domain?>"><?=$country->name?></a>
                            </li>            
                        <? } ?>
                    </ul>
                    <? } ?>
                    <span class="right">© realtynova.ru, 2012</span>
                </div>
            </div>
        </div>

        <? // echo View::factory('message-container'); ?>
        <? // echo View::factory('components/banner'); ?>
        <? // echo View::factory('components/zvonok'); ?>
        <? // echo View::factory('components/reformal'); ?>

        <?= View::factory('components/notifies'); ?>
        <?= View::factory('components/box'); ?>
        <?= View::factory('components/ajax-loader'); ?>
        <?= View::factory('components/core'); ?>
        <?= View::factory('components/cities'); ?>

        <?=View::factory('components/liveinternet')?>
    </body>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.dropdown-toggle').dropdown();
        });
    </script>
</html>

