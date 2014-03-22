<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtmll" xml:lang="ru" lang="ru">
    <head>
        <title><?=$title?> | <?=APPLICATION_TITLE?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?=Text::limit_chars(Text::desc($desc),160,'...')?>" />
        <meta name="keywords" content="<?=$keywords?>" />
        <?= $header ?>
    </head>
    <body>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2" class="head">
                    <span>Система управления сайтом</span>
                </td>
            </tr>
            <tr>
                <td id="menu">
                    <p>Приветствуем Вас, <strong><?=$user?></strong></p>
                    <h3>АДМИНИСТРАТОРУ</h3>
                    <ul>
                        <a id="menu-users" href="/users" class="button"><span>Пользователи</span></a>
                        <a id="menu-admin" href="/admin" class="button"><span>Настройки</span></a>
                    </ul>
                    <h3>МОДЕРАТОРУ</h3>
                    <ul>
                        <a id="menu-posts" href="/posts_admin" class="button"><span>Объявления</span></a>
                        <a id="menu-countries" href="/countries" class="button"><span>Страны</span></a>
                        <a id="menu-seoadmin" href="/seoadmin" class="button"><span>Описания страниц</span></a>
                        <a id="menu-pages" href="/page/admin" class="button"><span>Страницы</span></a>
                        <a id="menu-news" href="/news" class="button"><span>Новости</span></a>
                        <a id="menu-menu" href="/menu" class="button"><span>Блок меню</span></a>
                    </ul>
                    <h3>ОБЩЕЕ</h3>
                    <ul>
                        <div onclick="document.location.href = '/'" class="button"><span>На сайт</span></div>
                        <div data-link="/auth/logout" class="button senddata"><span>Выход</span></div>
                    </ul>
                </td>
                <td id="content">
                    <h4>&rarr; <?=$title?></h4>
                    <?=$content?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="footer">
                    &copy; 36n6.ru, 2012
                </td>
            </tr>


        </table>
        <div class="main">


        </div>

        <?= View::factory('components/notifies'); ?>
        <?= View::factory('components/core'); ?>
        <?= View::factory('components/ajax-loader'); ?>
        <?= View::factory('components/message'); ?>
        <?= View::factory('components/box'); ?>

    </body>
</html>