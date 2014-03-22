<div class="span3">
    <?= View::factory('columns/nedv') ?>
    <?= View::factory('columns/cabinet') ?>
    <?= View::factory('columns/news') ?>
</div>
<div class="span6 detail">

    <? $flat = $advert->flat; $photos = $flat->advert->photos->find_all(); ?>

    <div class="head">
        <h1>Продам <?=$flat->rooms?>-комнатную квартиру</h1>
    </div>
    <? if ($photos->count() > 0) { ?>
        <ul id="demo">
            <? foreach($photos as $photo) { ?>
            <li>
                <img src="<?=$photo->path()?>">
                <label>Квартира</label>
            </li>
            <? } ?>
        </ul>
        <ul id="thumbs">
            <? foreach($photos as $photo) { ?>
                <li><img src="<?=$photo->thumb()?>"></li>
            <? } ?>
        </ul>
    <? } ?>
    <table>
        <tbody><tr>
                <td>Дополнительная информация:</td>
                <td><?=$advert->desc?></td>
            </tr>
        </tbody></table>
    <table>
        <tbody><tr class="tittle"><td colspan="2">Контактная информация</td></tr>
            <tr>
                <td>E-mail:</td>
                <td><?=$advert->user->email?></td>
            </tr>
            <tr>
                <td>Телефон:</td>
                <td><?=$advert->user->phone?></td>
            </tr>
            <tr>
                <td>Адрес:</td>
                <td><?=$flat->adres?> <br><a href="#">Показать на карте</a></td>
            </tr>
        </tbody></table>
    <table>
        <tbody><tr class="tittle"><td colspan="2">Описание дома</td></tr>
            <tr>
                <td>Материал:</td>
                <td><?=$flat->type->name?></td>
            </tr>
            <tr>
                <td>Тип:</td>
                <td><?=$flat->construction->name?></td>
            </tr>
            <tr>
                <td>Этажность:</td>
                <td><?=Text::chislitelnie($flat->floors,array('этаж','этажа','этажей'))?></td>
            </tr>
            <tr>
                <td>Лифт (лифт?!):</td>
                <td>Пасажирский</td>
            </tr>
        </tbody></table>
    <table>
        <tbody><tr class="tittle"><td colspan="2">Информация о квартире</td></tr>
            <tr>
                <td>Количество комнат:</td>
                <td><?=$flat->rooms?></td>
            </tr>
            <tr>
                <td>Площадь комнат:</td>
                <td><?=$flat->square?></td>
            </tr>
            <tr>
                <td>Площадь(общ./жил./кух.) (???):</td>
                <td>17.22 м<sup>2</sup>/12.21 м<sup>2</sup>/17.93 м<sup>2</sup></td>
            </tr>
            <tr>
                <td>Этаж:</td>
                <td><?=$flat->floor?></td>
            </tr>
            <tr>
                <td>Высота потолков (???):</td>
                <td>3 м</td>
            </tr>
            <tr>
                <td>Пол (???):</td>
                <td>Отсутствует</td>
            </tr>
            <tr>
                <td>Санузел (???):</td>
                <td>два</td>
            </tr>
            <tr>
                <td>Телефон (???):</td>
                <td>Отсутствует</td>
            </tr>

        </tbody></table>
    <div class="head">
        <span>Быстрый переход: <a href="/">купить квартиру на первичном рынке в <?=$advert->city->name_rp?></a></span>
    </div>
</div>
<div class="span3">
    <?= View::factory('columns/news') ?>
</div>