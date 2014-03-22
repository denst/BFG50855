<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtmll" xml:lang="ru" lang="ru">
    <head>
        <title><?= ($_seo->loaded()) ? $_seo->title : $title ?> | <?= APPLICATION_TITLE ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?= ($_seo->loaded()) ? $_seo->desc : Text::limit_chars(Text::desc($desc), 160, '...') ?>" />
        <meta name="keywords" content="<?= ($_seo->loaded()) ? $_seo->keywords : $keywords ?>" />
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
                            <li><a onclick="$('#cities-container,#cities-background').show(); return false;" href="#"><?=$location->name?></a></li>
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

                    <ul class="nav">
                        <li class="active">
                            <a href="#">
                                О сервисе
                            </a>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="">
                            <a href="#">
                                Помощь
                            </a>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="">
                            <a href="#">
                                Новости
                            </a>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="">
                            <a href="#">
                                Реклама на сайте
                            </a>
                        </li>
                        <li class="divider-vertical"></li>
                        <li class="">
                            <a href="#">
                                Обратная связь
                            </a>
                        </li>
                    </ul>
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

