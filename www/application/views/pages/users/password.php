<h3>Изменение пароля пользователя <?=$user?></h3>
<table class="light-table" id="change_password">
    <tr>
        <td>Новый пароль</td>
        <td>
            <input type="text" name="pass" data-validation="notempty;minlength:5" />
            <?=Form::validation('pass')?>
        </td>
    </tr>
    <tr>
        <td>Повторите пароль</td>
        <td>
            <input type="text" name="retpass" data-validation="notempty;minlength:5" />
            <?=Form::validation('retpass')?>
        </td>
    </tr>
</table>
<div class="btn senddata-token" data-input="#change_password" data-link="/users/password/<?=$user->id?>">Изменить пароль</div>
<div class="clear"></div>