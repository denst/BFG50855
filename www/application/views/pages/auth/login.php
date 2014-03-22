<? if(isset($error_message)):?>
<div id="notifies">
    <div class="notify" id="notify-5" style="opacity: 1;">  
        <div class="notify-close" onclick="$(this).parent().remove()"></div>
        <div class="notify-head"> 
          <div title="Произошла ошибка" class="notify-head-icon">
              <img src="/themes/images/components/notify/error.png">
          </div>
          <div class="notify-text"><?= $error_message?></div>
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<? endif?>
<div class="span3">
</div>
<div class="span6 options login">

    <h1>Добро пожаловать, друг!</h1>
    
    <fieldset><?= $settings['form_authorization']?></fieldset>
    
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


