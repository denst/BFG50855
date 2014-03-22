<div class="span3">
    <?= View::factory('columns/cabinet') ?>
</div>
<div class="span6 options">

        <div class="head">
            <h1>Контактная информация</h1>
        </div>

        <div id="params">
            <fieldset>
                <div style="visibility: hidden"><a href="#">Смена пароля</a></div>
            </fieldset>
            <fieldset>
                <label>Электронная почта:</label>
                 <div><input data-validation="notempty" name="email" value="<?=$user->email?>" type="text"></div>
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
