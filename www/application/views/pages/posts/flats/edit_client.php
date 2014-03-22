<? if ($flat->loaded()) { ?>
    <h2>Редактирование объявления</h2>
<? } else { ?>
    <h2>Создание нового объявления</h2>
<? } ?>
<table style="width: 100%" id="redakt" border="1" bordercolor="#ccc" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td class="td7">Тип объявления</td>
            <td>
                <?=Form::select('types_id',$types,$flat->advert->adverts_types_id)?>
                <?=Form::validation('types_id')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Тип постройки</td>
            <td>
                <?=Form::select('constructions_id',$constructions,$flat->adverts_types_flats_contructiontypes_id)?>
                <?=Form::validation('constructions_id')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Тип квартиры</td>
            <td>
                <?=Form::select('flats_types_id',$flats_types,$flat->adverts_types_flats_types_id)?>
                <?=Form::validation('flats_types_id')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Название</td>
            <td>
                <input name="title" data-validation="notempty;" type="text" value="<?=$flat->advert->title?>">
                <?=Form::validation('title')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Описание</td>
            <td>
                <textarea name="desc" cols="50" rows="10" data-validation="notempty;"><?=$flat->advert->desc?></textarea>
                <?=Form::validation('desc')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Количество комнат</td>
            <td>
                <input name="rooms" data-validation="notempty;isint" type="text" value="<?=$flat->rooms?>">
                <?=Form::validation('rooms')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Этаж</td>
            <td>
                <input name="floor" data-validation="isint" type="text" value="<?=$flat->floor?>">
                <?=Form::validation('floor')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Количество этажей</td>
            <td>
                <input name="floors" data-validation="isint" type="text" value="<?=$flat->floors?>">
                <?=Form::validation('floors')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Площадь</td>
            <td>
                <input name="square" data-validation="isint" type="text" value="<?=$flat->square?>">
                <?=Form::validation('square')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Адрес</td>
            <td>
                <input name="adres" data-validation="" type="text" value="<?=$flat->adres?>">
                <?=Form::validation('adres')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Цена</td>
            <td>
                <input style="width: 100px; position: relative; top: -5px;" name="price" data-validation="isint;notempty" type="text" value="<?=$flat->advert->price?>">
                <?=Form::select('price_types_id',$price_types,$flat->advert->price_types_id,array('style'=>'width:100px;'))?>
                <?=Form::validation('price')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Город</td>
            <td>
                <?=Form::select('cities_id',$cities,$flat->advert->cities_id)?>
                <?=Form::validation('cities_id')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Срок аренды</td>
            <td>
                <input name="srokarendy" data-validation="isint" type="text" value="<?=$flat->srokarendy?>">
                <?=Form::validation('srokarendy')?>
            </td>
        </tr>
        <tr>
            <td class="td7">Фотографии</td>
            <td>
                <div id="upload-image"></div>
                <div id="photos">
                    <? if ($flat->loaded()) {
                        foreach($flat->advert->photos->find_all() as $photo) { ?>

                            <div class="photo" style="background-image: url(<?=$photo->thumb()?>)">
                                <div class="setmain" onclick="Gallery.setMainPhoto($(this).parent())"></div>
                                <div class="close" onclick="Gallery.removePhoto(this);"></div>
                                <input type="hidden" name="photos[]" value="<?=$photo->id?>" />
                            </div>

                        <? }
                    } ?>
                </div>
                <input type="hidden" name="photos-main" id="photos-main" value="<?=$flat->advert->main_photo_id?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <? if ($flat->loaded()) { ?>
                    <div class="senddata-token btn btn-primary" data-link="/posts_flats/edit/<?=$flat->id?>" data-input="#redakt">Применить изменения</div>
                <? } else { ?>
                    <div class="senddata-token btn btn-primary" data-link="/posts_flats/add" data-input="#redakt">Создать объявление</div>
                <? } ?>
            </td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    $('#redakt select').chosen();

    Upload.init('upload-image','<?= Security::token()?>',function(data) {

        var cont = $('#photos');
        $('.clear',cont).remove();
        $(cont).append('<div class="photo" style="background-image: url('+data.thumb+')"> <div class="setmain" onclick="Gallery.setMainPhoto($(this).parent())"></div> <div class="close" onclick="Gallery.removePhoto(this);"></div> <input type="hidden" name="photos[]" value="'+data.id+'" /> </div>');
        $(cont).append('<div class="clear"></div>');

    },'*.jpg;*.jpeg;*.png;*.gif','Изображения','normal', function() {
        // Когда все фотографии загрузятся на сервер, послать их на сервер
        Notify.message('Все фотографии успешно загружены', 'completed');
        $('#photos-main').val($('#photos input').val());
    });

</script>

