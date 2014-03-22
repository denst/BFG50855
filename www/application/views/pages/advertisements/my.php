<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 options">

        <div class="head">
            <h1>Мои объявления</h1>
        </div>

        <a href="/advertisements_flats/add" class="btn btn-small">Добавить объявление</a>
        <table class="mytab">
            <thead><tr class="tittle">
                <th>Дата</th>
                <th>ID</th>
                <th>Фото</th>
                <th>Тип</th>
                <th>Город</th>
                <th>Улица</th>
                <th>Цена, <i>руб.</i></th>
                <th>Этаж</th>
                <th colspan="2">Площадь, <i>м<sup>2</sup></i></th></tr></thead>
            <tbody>
            <? foreach($adverts as $advert) { ?>
                <tr>
                    <td><?=Text::humanDate($advert->time)?></td>
                    <td><?=$advert->id?></td>
                    <td><?=$advert->avatar()?></td>
                    <td><?=$advert->category->name?></td>
                    <td><?=$advert->city->name?></td>
                    <td><?=$advert->flat->adres?></td>
                    <td><?=$advert->price?></td>
                    <td><?=$advert->flat->floor?> / <?=$advert->flat->floors?></td>
                    <td><?=$advert->flat->square?></td>
                    <td>
                        <a class="edit" href="/advertisements_flats/edit/<?=$advert->flat->id?>"></a>
                        <a class="dell" href="/advertisements_flats/delete/<?=$advert->flat->id?>"></a>
                    </td>
                </tr>
            <? } ?>
        </tbody></table>

        <a href="#">Смена пароля</a>
        <h2 class="tittle">Контактная информация</h2>
        <div id="params">
            <fieldset>
                <label>Электронная почта:</label>
                <div><input name="email" value="<?=$user->email?>" type="text"></div>
            </fieldset>
            <fieldset>
                <label>Имя:</label>
                <div><input name="username" value="<?=$user->username?>" type="text"></div>
            </fieldset>
            <fieldset>
                <label>Город:</label>
                <div><?=Form::select('cities_id',Arr::Make2Array(ORM::factory('city')->find_all(), 'id', 'name'),$user->cities_id)?></div>
            </fieldset>
            <fieldset>
                <label>Телефон:</label>
                <div class="fone"><input name="phone_p1" value="" type="text"><input name="phone_p2" value="" type="text"></div>
            </fieldset>
            <fieldset>
                <label>&nbsp;</label>
                <div><input class="btn btn-warning senddata-token" data-link="/auth/updateparams" data-input="#params" value="Сохранить" type="submit"></div>
            </fieldset>
        </div>
</div>
<div class="span3">
    <?= View::factory('columns/news') ?>
</div>