<div class="span3">

    <?=View::factory('columns/nedv')?>

    <?=View::factory('columns/cabinet')?>

</div>
<div class="span6 options">
    <div class="head" style="height: auto">

        <h1><?=$_seo->h1?></h1>

        <div class="clear"></div>

        <p><?=$_seo->main?></p>

    </div>

    <?=$pager?>

    <ul class="order_list">
        <? foreach($adverts as $advert) { ?>

        <li>
            <img style="max-width: 180px;" src="<?=$advert->photo->thumb()?>" alt="<?=$advert->title?>" />

            <span class="price"><?=$advert->price?> <?=$advert->price_type->name?></span>

            <a onclick="Navigation.reloadFullPage('<?=$advert->link()?>');" href="<?=$advert->link()?>"><zagolovok><?=$advert->title?></zagolovok></a>

            <p class="date"><?= Text::humanDate($advert->time)?></p>

            <?=Text::limit_chars($advert->desc, 500, '...');?>

        </li>

        <? } ?>

    </ul>

    <? if ($adverts->count() == 0) { ?>

        <h3>Объявлений нет</h3>

        <p>Извините, но в данный момент здесь нет ни одного объявления.</p>

    <? } ?>

    <?=$pager?>

</div>

<div class="span3">

    <?=View::factory('columns/news')?>

    <?=View::factory('columns/posts',array(

        'cat' => $cat,

        'type' => $type

    ))?>

</div>