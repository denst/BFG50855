<div class="span3">
</div>
<div class="span6 options">
    <div class="head">
        <h1>Регистрация</h1>
    </div>

    <fieldset>Уважаемый посетитель! Для размещения информации на сайте необходимо
        зарегистрироваться. Если вы уже зарегистрированы на нашем сайте, 
        пожалуйста, авторизуйтесь.</fieldset>

    <form class="board" id="register-form">
        <fieldset>
            <label>Электронная почта:</label>
            <div><input data-validation="notempty;isemail" name="email" type="text" value="" /></div>
        </fieldset>
        <fieldset>
            <label>Имя:</label>
            <div><input data-validation="notempty" name="login" type="text" value="" /></div>
        </fieldset>
        <fieldset>
            <label>Город:</label>
            <div>
                <?
                    if (!empty($location->city)) {
                        $city_param = array($location->city->id => $location->city->name);
                    } else {
                        $city_param = null;
                    }
                ?>
                <?=Form::search('city_id',array('model'=>'city'),'Укажите город',$city_param)?>
                <?=Form::validation('city_id')?>
            </div>
        </fieldset>
        <fieldset>
            <label>Пароль:</label>
            <div><input data-validation="notempty" name="password" type="password" /></div>
        </fieldset>
        <fieldset>
            <label>Подтверждение:</label>
            <div><input data-validation="notempty" name="password_retry" type="password" /></div>
        </fieldset>
        <fieldset>
            <label>Телефон:</label>
            <div class="fone">
                <input data-validation="notempty" name="phone_code" type="text" value="" />
                <input data-validation="notempty" name="phone_number" type="text" value="" />
            </div>
        </fieldset>
        <fieldset>
            <p>Регистрируясь на сайте вы подтверждаете свое согласие с <a href="">правилами размещения объявлений на портале</a></p>
        </fieldset>
        <fieldset>
            <div style="padding: 0 3px; width: 100px; margin: 15px 0 0 150px;" data-link="/auth/register" data-input="#register-form" class="btn btn-warning senddata-token">Согласен</div>
        </fieldset>
    </form>

</div>
<div class="span3">
</div>


