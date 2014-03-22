<div class="head">
    <li><b>Жилая недвижимость</b></li>
</div>

<?  $route = Request::current()->route();
    if (Route::name($route) == 'commerce_adverts') {
        $routetype = Request::current()->param('type');
        if (! $routetype) $routetype = ADVERTS_TYPES_PRODAJA_STR;
        $routecommtype = Request::current()->param('commtype');
        $commtypes = ORM::factory('Adverts_Types_Commerce_Type')->order_by('name')->find_all(); ?>
        <ul class="left_menu">
            <? $uri = Request::current()->uri(); ?>
            <li <? if($uri == 'commerce/prodaja'){?>class="active"<?}?>><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>">Продажа ком. недвижимости</a></li>
            <li <? if($uri == 'commerce/arenda') {?>class="active"<?}?>><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>">Аренда ком. недвижимости</a></li>
        </ul>
        <li>Разделы <?if($routetype=='arenda'){?>аренды<?}else{?>продажи<?}?></li>
        <ul class="left_menu">
        <? foreach ($commtypes as $commtype) { ?>
            <? $current = ($routecommtype == $commtype->lat_name) ? TRUE : FALSE; ?>
            <li <? if ($current) { ?>class="active"<?}?>><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>/<?=$routetype?>/<?=$commtype->lat_name?>"><?=$commtype->name?></a></li>
        <? } ?>
        </ul>
<? } else { ?>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_FLAT_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже квартир">Продажа квартир</a></li>
        <li><a href="/<?=ADVERTS_CATS_FLAT_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде квартир">Аренда квартир</a></li>
    </ul>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_ROOM_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже комнат">Продажа комнат</a></li>
        <li><a href="/<?=ADVERTS_CATS_ROOM_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде комнат">Аренда комнат</a></li>
    </ul>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_HOUSE_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже домов">Продажа домов</a></li>
        <li><a href="/<?=ADVERTS_CATS_HOUSE_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде домов">Аренда домов</a></li>
    </ul>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_GARAGE_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже гаражей">Продажа гаражей</a></li>
        <li><a href="/<?=ADVERTS_CATS_GARAGE_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде гаражей">Аренда гаражей</a></li>
    </ul>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_TERRAIN_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже земли">Продажа земельных участков</a></li>
        <li><a href="/<?=ADVERTS_CATS_TERRAIN_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде земли">Аренда земельных участков</a></li>
    </ul>
    <ul class="left_menu">
        <li><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>/<?=ADVERTS_TYPES_PRODAJA_STR?>" title="Объявления о продаже коммерческой недвижимости">Продажа комм. недвижимости</a></li>
        <li><a href="/<?=ADVERTS_CATS_COMMERCE_STR?>/<?=ADVERTS_TYPES_ARENDA_STR?>" title="Объявления об аренде коммерческой недвижимости">Аренда комм. недвижимости</a></li>
    </ul>
<? } ?>