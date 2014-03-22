<div class="span3">
    <?= View::factory('columns/nedv') ?>
    <?= View::factory('columns/cabinet') ?>
    <?= View::factory('columns/news') ?>
</div>
<div class="span6 options">
    <div class="head">
        <h1>Создать объявление</h1>
    </div>

    <div id="redakt" class="newform">
        <fieldset>
            <label>Ваше имя:</label>
            <div><span><?=$user->username?></span></div>
        </fieldset>
        <fieldset>
            <label>Электронная почта:</label>
            <div><span><?=$user->email?></span></div>
        </fieldset>
        <fieldset>
            <label>Номер телефона:</label>
            <div><span><?=$user->phone?></span></div>
        </fieldset>
        <a class="btn btn-info btn-small" href="">Изменить данные</a>
        <fieldset>
            <label>&nbsp;</label>
            <div>
                <label class="checkbox">
                    <input name="dontsendemails" type="checkbox" id="chek1"> Я не хочу получать вопросы от покупателей отелей по e-mail
                </label>
            </div>
        </fieldset>
        <fieldset>
            <label>Город:</label>
            <div>
                <?=Form::select('cities_id',$cities, ($flat->loaded()) ? $flat->advert->cities_id : (!empty($location->city)) ? $location->city->id : null )?>
                <?=Form::validation('cities_id')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Категория:</label>
            <div><select>
                    <option>Квартиры</option>
                </select></div>
        </fieldset>
        <fieldset>
            <label>Выберите параметры:</label>
            <div>
                <?=Form::select('types_id',$types,$flat->advert->adverts_types_id)?>
                <?=Form::validation('types_id')?>
                <br>
                <select name="rooms" data-validation="notempty;isint">
                    <option value="">Количество комнат</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>
                <?=Form::validation('rooms')?>
                <select name="srokarendy">
                    <option value="">Срок аренды</option>
                    <option value="1">Какие здесь должны быть значения-то?</option>
                </select>
                <?=Form::validation('srokarendy')?>

                <input type="text" placeholder="Этаж" name="floor" value="" data-validation="isint" />
                <input type="text" placeholder="Этажей" name="floors" value="" data-validation="isint" />
                <?=Form::validation('floor')?>
                <?=Form::validation('floors')?>

                <?=Form::select('constructions_id',$constructions,$flat->adverts_types_flats_contructiontypes_id)?>
                <?=Form::validation('constructions_id')?>

                <?=Form::select('flats_types_id',$flats_types,$flat->adverts_types_flats_types_id)?>
                <?=Form::validation('flats_types_id')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Площадь:</label>
            <div class="setv">
                <input name="square" data-validation="isint" type="text" value="<?=$flat->advert->square?>"> м<sup>2</sup>
                <?=Form::validation('square')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Адрес:</label>
            <div class="allw">
                <input name="adres" data-validation="" type="text" value="<?=$flat->advert->adres?>">
                <?=Form::validation('adres')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Название обьявления:</label>
            <div class="allw">
                <input name="title" data-validation="notempty;" type="text" value="<?=$flat->advert->title?>">
                <?=Form::validation('title')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Описание обьявления:</label>
            <div class="allw">
                <textarea name="desc" cols="50" rows="5" data-validation="notempty;"><?=$flat->advert->desc?></textarea>
                <?=Form::validation('desc')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Арендная плата:</label>
            <div class="setv">
                <input style="width: 100px;" name="price" data-validation="isint;notempty" type="text" value="<?=$flat->advert->price?>">
                <?=Form::select('price_types_id',$price_types,$flat->advert->price_types_id,array('style'=>'width:100px;'))?>
                <?=Form::validation('price')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Фотографии: <br> <i>До 10 фотографий</i></label>
            <div class="setv">
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
            </div>
        </fieldset>
        <fieldset>
            <label>&nbsp;</label>
            <div>
                <? if ($flat->loaded()) { ?>
                    <div class="senddata-token btn btn-primary" data-link="/posts_flats/edit/<?=$flat->id?>" data-input="#redakt">Применить изменения</div>
                <? } else { ?>
                    <div class="senddata-token btn btn-primary" data-link="/posts_flats/add" data-input="#redakt">Создать объявление</div>
                <? } ?>
            </div>
        </fieldset>
    </div>
</div>
<div class="span3">
    <?= View::factory('columns/news') ?>
</div>

<script type="text/javascript">
    //$('#redakt select').chosen();

    $(document).ready(function(){
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
    });
</script>

