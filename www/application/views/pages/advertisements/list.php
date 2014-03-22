<div class="span3">

    <?=View::factory('columns/nedv')?>

    <?=View::factory('columns/cabinet')?>

    <?=View::factory('columns/news')?>

</div>

<div class="span6 options">



    <div class="head">

        <h1><?=$_seo->h1?></h1>

        <div class="clear"></div>

        <p><?=$_seo->main?></p>

    </div>

    <?=$pager?>

    <ul class="order_list">

        <? foreach($adverts as $advert) { ?>

        <li>

            <img src="<?=$advert->photo->thumb()?>" alt="<?=$advert->title?>" />

            <span class="price"><?=$advert->price?> <?=$advert->price_type->name?></span>

            <zagolovok><a href="<?=$advert->link()?>"><?=$advert->title?></a></zagolovok>

            <p class="date"><?= Text::humanDate($advert->time)?></p>

            <?=$advert->desc?>

        </li>

        <? } ?>

    </ul>

    <?=$pager?>

</div>

<div class="span3">

    <?=View::factory('columns/news')?>

</div>