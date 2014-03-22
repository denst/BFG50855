<div class="span3">
</div>
<div class="span6 options newpassword">
    <div class="head">
        <h1>Установите новый пароль</h1>
    </div>

    <fieldset><?= $settings['recoverypassword_step2']?></fieldset>

    <form class="board" id="newpassword-form">
        <fieldset>
            <label>Пароль:</label>
            <div><input data-validation="notempty" name="password" type="password" /></div>
        </fieldset>
        <fieldset>
            <label>Подтверждение:</label>
            <div><input data-validation="notempty" name="password_retry" type="password" /></div>
        </fieldset>
        <fieldset>
            <div style="padding: 0 3px; width: 100px; margin: 15px 0 0 150px;" data-link="/auth/newpassword" data-input="#newpassword-form" class="btn btn-warning senddata-token">Cохранить</div>
        </fieldset>
        <input type="hidden" name="user_id" value="<?= $user_id?>">
    </form>

</div>
<div class="span3">
</div>


