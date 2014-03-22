<div class="span3">
</div>
<div class="span6 options">

    <h1>Добро пожаловать, друг!</h1>
    <p>Пожалуйста, введи свои данные учетной записи, чтобы мы знали, кто ты.</p>
    <table id="login-form">
        <tr>
            <td>Email:</td>
            <td><input type="text" name="login" onkeypress="onEnter(event, function() { $('#pass').focus(); });" /></td>
        </tr>
        <tr>
            <td>Пароль:</td>
            <td><input id="pass" type="password" name="password" onkeypress="onEnter(event, function() { $('#loginbtn').click(); });" /></td>
        </tr>
    </table>
    <div id="loginbtn" class="btn senddata-token" data-link="/auth/login" data-input="#login-form">Авторизоваться</div>
    <a class="btn ml10px" href="/auth/register">Регистрация</a>
    <div class="clear"></div>

    <hr/>

</div>
<div class="span3">
</div>


