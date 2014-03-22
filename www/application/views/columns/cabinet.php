<div class="head">
    <li>Личный кабинет</li>
</div>
<? if (!$auth->logged_in()) { ?>
    <form id="loginform">
        <input data-validation="notempty" type="text" name="login" onkeypress="onEnter(event, function() { $('#pass').focus(); });" placeholder="Email" />
        <input data-validation="notempty" id="pass" type="password" name="password" onkeypress="onEnter(event, function() { $('#loginbtn').click(); });" placeholder="Пароль" />
        <div id="loginbtn" class="btn btn-warning senddata-token" data-link="/auth/login" data-input="#loginform"><i></i>Вход</div>
        <a href="/auth/register">Нет логина?</a><a href="/auth/recoverypassword">Забыли пароль?</a>
    </form>
<? } else { ?>
    <ul class="left_menu">
        <li><a href="/posts/save">Добавить объявление</a></li>
        <li><a href="/posts/my">Мои объявления</a></li>
        <li><a href="/auth/settings">Настройки</a></li>
        <? if ($user->has('roles',ROLE_ADMIN)) { ?>
            <li><a href="/users" onclick="Navigation.reloadFullPage('/users'); return false;">Админка</a></li>
        <? } ?>
        <li><a href="/auth/logout">Выйти</a></li>
    </ul>
<? } ?>