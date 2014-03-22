<style>
    .board label {
        width: 60px;
    }
    .board div{
        padding: 0px !important;
    }
    #recoverypassword-form 
</style>
<div class="span3">
</div>
<div class="span6 options recovery">
    <div class="head">
        <h1>Форма восстановления пароля</h1>
    </div>

    <fieldset><?= $settings['recoverypassword_step1']?></fieldset>

    <form class="board" id="recoverypassword-form">
        <fieldset>
            <label>Email:</label>
            <div><input data-validation="notempty;isemail" name="email" type="text" value="" /></div>
            <div style="padding: 0 3px; width: 170px; margin-left: 20px;" data-link="/auth/recoverypassword" data-input="#recoverypassword-form" class="btn btn-warning senddata-token">Восстановить пароль</div>
        </fieldset>
    </form>

</div>
<div class="span3">
</div>


