<style>
.change-user{
    margin-left: 70px;
}
#redakt fieldset {
    margin-top: 0px;
}
#dontsendemails-container label{
    padding: 0px;
}
#dontsendemails-container input{
    margin-right: 3px;
    position: relative;
    top: 3px;
}
.options fieldset input[type=text], .search,

.options fieldset input[type=password]{width: 211px;font-size:12px}

.input-block, .adverts_params select, .adverts_params input{
    margin-bottom: 20px !important;
}
</style>
<div class="span3">
    <?= View::factory('columns/nedv') ?>
    <?= View::factory('columns/cabinet') ?>
    <?= View::factory('columns/news') ?>
</div>
<div class="span6 options">
    <div class="head">
        <? if ($advert->loaded()) { ?>
            <h2>Редактирование объявления</h2>
        <? } else { ?>
            <h2>Создание нового объявления</h2>
        <? } ?>
    </div>

    <div id="redakt" class="newform">
        <fieldset>
            <label>Ваше имя:</label>
            <div>
                <span><?=$user->username?></span>
                <span class="change-user"><a href="/auth/settings">Изменить имя</a></span>
            </div>
        </fieldset>
        <fieldset>
            <label>Электронная почта:</label>
            <div>
                <span><?=$user->email?></span>
                <span class="change-user"><a href="/auth/settings">Изменить e-mail</a></span>
            </div>
        </fieldset>
        <fieldset>
            <label>&nbsp;</label>
            <div id="dontsendemails-container">
                <label class="checkbox">
                    <input name="dontsendemails" type="checkbox" id="chek1"> Я не хочу получать вопросы от покупателей отелей по e-mail
                </label>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Номер телефона:</label>
            <div>
                <span>
                    <input name="phone" data-validation="isphone;notempty" type="text" value="<?=$user->phone?>" />
                    <?=Form::validation('phone')?>
                </span>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Город:</label>
            <div>
                <?
                    if ($advert->loaded()) {
                        $city_param = array($advert->city->id => $advert->city->name);
                    } else {
                        if (!empty($location->city)) {
                            $city_param = array($location->city->id => $location->city->name);
                        } else {
                            $city_param = array($user->city->id => $user->city->name);
                        }
                    }
                ?>
                <?=Form::search('cities_id',array('model'=>'city'),'Укажите город',$city_param,null,false,'width: 300px')?>
                <? // echo Form::select('cities_id',$cities,$city_id,array('data-validation'=>'notempty'))?>
                <?=Form::validation('cities_id')?>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Район города:</label>
            <div>
                <? $areas = Arr::Make2Array(ORM::factory('cities_area')->where('cities_id','=',key($city_param))->find_all(),'id','name'); ?>
                <?=Form::select('city_area',array('' => 'Укажите район') + $areas, $advert->cities_areas_id);?>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Категория:</label>
            <div>
                <?=Form::select('categories_id',$categories,$advert->adverts_categories_id,array(
                    'onchange' => "showConf($(this).val())"
                ))?>
                <?=Form::validation('categories_id')?>
            </div>
        </fieldset>

        <fieldset class="input-block">
            <label>Выберите параметры:</label>
            <div>
                <div style="display:none" class="adverts_params advert-param-group-<?=ADVERTS_CATS_FLAT?> advert-param-group-<?=ADVERTS_CATS_ROOM?>">
                    <?
                        if ($advert->adverts_categories_id == ADVERTS_CATS_FLAT) {
                            $info = $advert->flat;
                        } else {
                            $info = $advert->room;
                        }
                    ?>
                    <select name="rooms" data-validation="notempty;isnumeric">
                        <option value="">Количество комнат</option>
                        <option <? if ($info->rooms == 1) {?>selected<?}?> value="1">1</option>
                        <option <? if ($info->rooms == 2) {?>selected<?}?> value="2">2</option>
                        <option <? if ($info->rooms == 3) {?>selected<?}?> value="3">3</option>
                        <option <? if ($info->rooms == 4) {?>selected<?}?> value="4">4</option>
                        <option <? if ($info->rooms == 5) {?>selected<?}?> value="5">5</option>
                        <option <? if ($info->rooms == 6) {?>selected<?}?> value="6">6</option>
                        <option <? if ($info->rooms == 7) {?>selected<?}?> value="7">7</option>
                        <option <? if ($info->rooms == 8) {?>selected<?}?> value="8">8</option>
                    </select>
                    <?=Form::validation('rooms')?>

                    <input type="text" placeholder="Высота потолков (м)" name="ceilingheight" value="<?=$info->ceilingheight?>" data-validation="isnumeric" />
                    <?=Form::validation('ceilingheight')?>

                    <input type="text" placeholder="Этаж" name="floor" value="<?=$info->floor?>" data-validation="isnumeric" />
                    <input type="text" placeholder="Этажей" name="floors" value="<?=$info->floors?>" data-validation="isnumeric" />
                    <?=Form::validation('floor')?>
                    <?=Form::validation('floors')?>

                    <?=Form::select('constructions_id',$constructions,$info->adverts_types_flats_contructiontypes_id,array('data-validation'=>'notempty'))?>
                    <?=Form::validation('constructions_id')?>

                    <?=Form::select('flats_types_id',$flats_types,$info->adverts_types_flats_types_id,array('data-validation'=>'notempty'))?>
                    <?=Form::validation('flats_types_id')?>

                    <?=Form::select('lift',array(''=>'Лифт','0'=>'Нет','1'=>'Пассажирский','2'=>'Грузовой'),$info->lift)?>
                    <?=Form::validation('lift')?>

                    <?=Form::select('phone',array(''=>'Телефон','0'=>'Есть','1'=>'Нет'),$info->phone)?>
                    <?=Form::validation('phone')?>

                    <?=Form::select('adverts_types_flats_floortypes_id',$adverts_types_flats_floortypes,$info->adverts_types_flats_floortypes_id,array('data-validation'=>'notempty'))?>
                    <?=Form::validation('adverts_types_flats_floortypes_id')?>

                    <?=Form::select('adverts_types_flats_wctypes_id',$adverts_types_flats_wctypes,$info->adverts_types_flats_wctypes_id,array('data-validation'=>'notempty'))?>
                    <?=Form::validation('adverts_types_flats_wctypes_id')?>
                </div>

                <div style="display:none" class="adverts_params advert-param-group-<?=ADVERTS_CATS_HOUSE?>">
                    <?=Form::select('houses_types_id',$houses_types,$advert->house->adverts_types_houses_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('houses_types_id')?>

                    <?=Form::select('houses_materials_id',$houses_materials,$advert->house->adverts_types_houses_materials_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('houses_materials_id')?>

                    <?=Form::select('houses_terrains_id',$terrain_types,$advert->house->adverts_types_terrains_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('houses_terrains_id')?>

                    <input type="text" placeholder="До города (км)" name="house_dogoroda" value="<?=$advert->house->dogoroda?>" data-validation="isnumeric" />
                    <?=Form::validation('house_dogoroda')?>
                </div>
                <div style="display:none" class="adverts_params advert-param-group-<?=ADVERTS_CATS_TERRAIN?>">
                    <?=Form::select('terrains_properties_id',$terrains_properties,$advert->terrain->adverts_types_terrains_properties_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('terrains_properties_id')?>

                    <?=Form::select('terrains_types_id',$terrain_types,$advert->terrain->adverts_types_terrains_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('terrains_types_id')?>

                    <input type="text" placeholder="До города (км)" name="terrain_dogoroda" value="<?=$advert->terrain->dogoroda?>" data-validation="isnumeric" />
                    <?=Form::validation('house_dogoroda')?>
                </div>
                <div style="display:none" class="adverts_params advert-param-group-<?=ADVERTS_CATS_GARAGE?>">
                    <?=Form::select('garages_types_id',$garages_types,$advert->garage->garages_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('garages_types_id')?>
                </div>
                <div style="display:none" class="adverts_params advert-param-group-<?=ADVERTS_CATS_COMMERCE?>">
                    <?=Form::select('commerce_types_id',$commerce_types,$advert->commerce->commerce_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('commerce_types_id')?>

                    <?=Form::select('properties_types_id',$commerce_properties,$advert->commerce->properties_types_id,array('data-validation' => 'notempty'))?>
                    <?=Form::validation('properties_types_id')?>
                </div>
                <?=Form::select('types_id',$types,$advert->adverts_types_id,array(
                    'onchange' => "showType($(this).val())"
                ))?>
                <?=Form::validation('types_id')?>

                <?=Form::select('period',array(''=>'Срок аренды','0' => 'Длительно', '1' => 'На сутки'),$advert->period,array(
                    'data-validation' => 'notempty',
                    'style' => 'display: none'
                ))?>
                <?=Form::validation('period')?>
            </div>
        </fieldset>

        <fieldset class="input-block">
            <label>Площадь:</label>
            <div class="setv">
                <input name="square" data-validation="isnumeric" type="text" value="<?=$advert->square?>" style="margin-right: 3px;"> м<sup>2</sup>
                <?=Form::validation('square')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Адрес:</label>
            <div class="allw">
                <input onchange="Maps.trySearchLocation($(this).val(),0,event)"
                       onkeyup="Maps.trySearchLocation($(this).val(),0,event)"
                       name="adres" data-validation="" type="text" value="<?=$advert->adres?>">
                <span style="width: 200px; font-size: 12px;" id="search-location-0"></span>
                <?=Form::validation('adres')?>
                <div id="coord_0">
                    <input type="hidden" name="points_x" value="" />
                    <input type="hidden" name="points_y" value="" />
                </div>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Название обьявления:</label>
            <div class="allw">
                <input name="title" data-validation="notempty;" type="text" value="<?=$advert->title?>">
                <?=Form::validation('title')?>
            </div>
        </fieldset>
        <fieldset class="input-block">
            <label>Описание обьявления:</label>
            <div class="allw">
                <textarea name="desc" cols="50" rows="5" data-validation="notempty;"><?=$advert->desc?></textarea>
                <?=Form::validation('desc')?>
            </div>
        </fieldset>
        <fieldset  class="input-block">
            <label id="cost-type">Арендная плата:</label>
            <div class="setv">
                <input style="width: 100px; margin-right: 4px;" name="price" data-validation="isnumeric;notempty" type="text" value="<?=$advert->price?>">
                <?=Form::select('price_types_id',$price_types,$advert->price_types_id,array('style'=>'width:100px;'))?>
                <?=Form::validation('price')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Фотографии: <br> <i>До 10 фотографий</i></label>
            <div class="setv">
                <div id="upload-image"></div>
                <div id="photos">
                    <? if ($advert->loaded()) {
                        foreach($advert->photos->find_all() as $photo) { ?>

                            <div class="photo" style="background-image: url(<?=$photo->thumb()?>)">
                                <div class="setmain" onclick="Gallery.setMainPhoto($(this).parent())"></div>
                                <div class="close" onclick="Gallery.removePhoto(this);"></div>
                                <input type="hidden" name="photos[]" value="<?=$photo->id?>" />
                            </div>

                        <? }
                    } ?>
                </div>
                <input type="hidden" name="photos-main" id="photos-main" value="<?=$advert->main_photo_id?>" />
            </div>
        </fieldset>
        <fieldset>
            <label>&nbsp;</label>
            <div>
                <? if ($advert->loaded()) { ?>
                    <div class="senddata-token mt15px btn btn-primary" data-link="/posts/save/<?=$advert->id?>" data-input="#redakt">Применить изменения</div>
                <? } else { ?>
                    <div class="senddata-token mt15px btn btn-primary" data-link="/posts/save" data-input="#redakt">Создать объявление</div>
                <? } ?>
            </div>
        </fieldset>
    </div>
</div>
<div class="span3">
    <?= View::factory('columns/news') ?>
</div>

<script type="text/javascript">
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

        showConf('<?= empty($advert->adverts_categories_id) ? ADVERTS_CATS_FLAT : $advert->adverts_categories_id?>');
        showType('<?= empty($advert->types_id) ? 1 : $advert->types_id?>');
    });
    function showConf(type) {
        $('.adverts_params').hide();
        $('.advert-param-group-'+type).show();
    }
    function showType(type) {
        var type_val = '';
        switch(true) {
            case type == "1":
                type_val = 'Стоимость:';
            break;
            case type == "2":
                type_val = 'Стоимость аренды:';
            break;
            case type == "3":
                type_val = 'Стоимость покупки:';
            break;
            case type == "4":
                type_val = 'Стоимость:';
            break;
        }
        $('#cost-type').html(type_val);
        if (type == '2') { // Если тип объявления - сдача в аренду, то покажем выбиралку периода сдачи в аренду
            $('select[name="period"]').show();
        } else {
            $('select[name="period"]').hide();
        }
    }
</script>

