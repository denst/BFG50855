<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 options" style="min-height: 600px">

        <div class="head">
            <h1>Мои объявления</h1>
        </div>

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
</div>