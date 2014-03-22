<div class="span3">

    <?= View::factory('columns/nedv') ?>

    <?= View::factory('columns/cabinet') ?>

</div>
<div class="span6 detail">



    <div class="head">

        <h1><?=$advert->title?></h1>

        

    </div>

    <? $photos = $advert->photos->find_all(); ?>

    <? if ($photos->count() > 0) { ?>

        <ul id="demo">

            <li>

                <img id="mainphoto" src="<?=$photos[0]->path()?>">

                <label>Квартира</label>

            </li>

        </ul>

        <ul id="thumbs">

            <? foreach($photos as $photo) { ?>

                <li><img style="cursor: pointer" onmouseover="$('#mainphoto').attr('src',$(this).attr('data-img'))" data-img="<?=$photo->path()?>" src="<?=$photo->thumb()?>"></li>

            <? } ?>

        </ul>

    <? } else { ?>

        <ul id="demo">

            <li>

                <img id="mainphoto" src="/upload/nophoto.png">

                <label>Фотографии нет</label>

            </li>

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

                <td>Дата публикации:</td>

                <td><?= Text::humanDate($advert->time); ?></td>

            </tr>

            <tr>

                <td>E-mail:</td>

                <td><?= !empty($advert->email) ? $advert->email : $advert->user->email?></td>

            </tr>

            <tr>

                <td>Телефон:</td>

                <td><?= !empty($advert->phone) ? $advert->phone : $advert->user->phone?></td>

            </tr>

            <tr>

                <td>Адрес:</td>

                <td><?=$advert->adres?></td>

            </tr>

            <tr>

                <td>Цена:</td>

                <td><?=$advert->price?> <?=$advert->price_type->name?></td>

            </tr>

        </tbody>

    </table>



    <?

        switch ($advert->adverts_categories_id) {

            case ADVERTS_CATS_FLAT:

                include('show/flat.php');

            break;

            case ADVERTS_CATS_ROOM:

                include('show/room.php');

            break;

            case ADVERTS_CATS_HOUSE:

                include('show/house.php');

            break;

            case ADVERTS_CATS_TERRAIN:

                include('show/terrain.php');

            break;

            case ADVERTS_CATS_GARAGE:

                include('show/garage.php');

            break;

            case ADVERTS_CATS_COMMERCE:

                include('show/commerce.php');

            break;

        }

    ?>



<?    if (!empty($advert->x) && !empty($advert->y)){?>
    <table>

        <tbody><tr class="tittle">

                <td>Расположение на карте:</td>

            </tr>

        </tbody></table>
    <div id="ymaps" style="margin-top: 10px; width: 100%; height: 250px; border: 1px solid #ccc;"></div>

    <script type="text/javascript">

        $(document).ready(function(){

            <?

                $json_coords = array();

                $json_coords[] = array(

                    'x' => $advert->x,

                    'y' => $advert->y,

                    'text' => $advert->title,

                    'addr' => $advert->adres

                );

            ?>

            Maps.company(<?=json_encode($json_coords)?>,'ymaps');

        });

    </script>
<?
}
?>


    <div class="head">

        <? /* <span>Быстрый переход: <a href="/">купить квартиру на первичном рынке в <?=$advert->city->name_rp?></a></span> */ ?>

    </div>

</div>

<div class="span3">

    <? View::factory('columns/news') ?>

</div>