<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 options">

        <div class="head">
            <h1>Мои объявления</h1>
        </div>

        <a href="/posts/save" class="btn btn-small">Добавить объявление</a>
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
                    <td><a href="<?=$advert->link()?>"><?=$advert->id?></a></td>
                    <td><?=$advert->avatar()?></td>
                    <td><?=$advert->category->name?></td>
                    <td><?=$advert->city->name?></td>
                    <td><?=$advert->adres?></td>
                    <td><?=$advert->price?></td>
                    <td><?=$advert->flat->floor?> / <?=$advert->flat->floors?></td>
                    <td><?=$advert->square?></td>
                    <td>
                        <a class="edit" href="/posts/save/<?=$advert->id?>"></a>
                        <a class="dell" href="/posts/delete/<?=$advert->id?>"></a>
                    </td>
                </tr>
            <? } ?>
            </tbody>
        </table>

        <h2 class="tittle">Контактная информация</h2>
        <div id="params">
            <fieldset>
                <div><a href="#">Смена пароля</a></div>
            </fieldset>
            <fieldset>
                <label>Электронная почта:</label>
                <div><?=$user->email?></div>
            </fieldset>
            <fieldset>
                <label>Имя:</label>
                <div><input data-validation="notempty" name="username" value="<?=$user->username?>" type="text"></div>
            </fieldset>
            <fieldset>
                <label>Город:</label>
                <div class="mb10px">
                    <? // echo Form::search('cities_id',Arr::Make2Array(ORM::factory('city')->find_all(), 'id', 'name'),$user->cities_id)?>
                    <?=Form::search('cities_id', array('model'=>'city'), 'начните набирать название..',array($user->city->id => $user->city->name),false,'margin: 0;'); ?>
                </div>
            </fieldset>
            <fieldset>
                <label>Телефон:</label>
                <div><input data-validation="notempty;isphone" name="phone" value="<?=$user->phone?>" type="text"></div>
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