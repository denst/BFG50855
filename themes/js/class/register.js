Reg = new function() {

    $(document).ready(function() {

        // Если человек продолжил вводить текст, скрыть сообщение о ошибке
        $('#z-container-login input').keypress(function(){
            $('#z-container-login-error-auth').text('');
        });

    });

    this.sendLogin = function()
    {

        $('#z-container-login-error-auth').html('');

        var data={},isError=false;

        $('#logininputs input').each(function() {
            var $this=$(this);

            if (Validation.getInstance().validate($this)=='error') {
                isError = true;
                return;
            } else {
                if ($this.attr('type')=='checkbox') {
                    if ($this.is(':checked')) {
                        data[$this.attr('name')] = $this.val();
                    }
                } else {
                    data[$this.attr('name')] = $this.val();
                }
            }
        });

        // Если все нормально, посылаем форму.
        if(!isError){
            $('#loginform').get(0).submit();
        }

    }

    this.animationMethod = 'easeOutExpo';
    this.animationTime = 500;

    this.updateKeys = function(key1,key2)
    {
        $('#login-form input[type="hidden"]').attr('name',key1).val(key2);
        $('#register-inputs input[type="hidden"]').attr('name',key1).val(key2);
    }

    this.loginBox_minTop = '-300px';
    this.regBox_minTop = '-600px';

    this.showLoginBox = function()
    {
        this.closeRegister();
        var form = $('#login');

        if (form.css('display') == 'block')
        {
            this.closeLoginBox();
        } else {
            form.css({
                'top': this.loginBox_minTop,
                'display' : 'block'
            }).stop().animate({
                'top': 50
            },1000,'easeOutQuart');

            $('#username').focus();

            $('#z-container').css('display', 'none');
            $('#z-container-2').css('display', 'none');
            $('#z-container-login').css('display', 'none');
            $('#z-container-login').css('display', 'block');
        }

        return false;
    }

    this.closeLoginBox = function()
    {
        $('#login').stop().animate({'top': this.loginBox_minTop},500,'easeInBack',function(){
            $(this).css({'display' : 'none'});
        });
    }

    this.rememberClick = function() {
        var checkbox = $('#input-remember-checkbox');
        var input = $('#input-remember-input');
        if (checkbox.css('opacity') == '0')
        {
            input.attr('checked','checked');
            checkbox.stop().animate({'opacity' : '1'},300,'linear');
        } else {
            input.attr('checked',null);
            checkbox.stop().animate({'opacity' : '0'},300,'linear');
        }
    }

    this.resetRegister = function() {
        $('#register-inputs').css('display','block');
        $('#register-progress').css('display','none');
    }

    this.showRegister = function() {
        this.closeLoginBox();
        var form = $('#register-form');

        $('#register-inputs').css('display','block');
        $('#register-progress').css('display','none');

        if (form.css('display') == 'block')
        {
            this.closeRegister();
        } else {
            form.css({
                'top': this.regBox_minTop,
                'display' : 'block'
            }).stop().animate({
                'top': 50
            },1000,'easeOutQuart');

            $('#register-form #input-login').focus();

            $('#z-container').css('display', 'none');
            $('#z-container-2').css('display', 'none');
            $('#z-container-login').css('display', 'none');
            $('#z-container-login').css('display', 'block');
        }
    }

    this.closeRegister = function() {
        $('#register-form')
            .stop()
            .animate({'top': this.regBox_minTop},500,'easeInBack',function(){
                $(this).css({'display' : 'none'});
            });
    }

    this.showRegisterSuccess = function() {
        $('#z-container-2,#z-container-registration,#z-container-registration-success').animate({
            'top':'-868'
        },1000,animationMethod);
        $('#register').animate({
            marginTop:'100'
        },1000);
    }

    this.forgotPassword = function(get_email_from_register) {

        if (get_email_from_register == true) {
            $('#restore-email').val($('#input-email').val());
        }

        Core.getInputData('#forgotinputs', function(data) {
            Message.sendData('/users/recoverypassword', data);
        });

    }

    this.toggleForgotPassword = function(element) {

        if (element.checked) {

            $('#forgotinputs').show();
            $('#logininputs').hide();

            $('#restore-email').val($('#username').val());

        } else {

            $('#forgotinputs').hide();
            $('#logininputs').show();

        }

    }

    this.Register = function() {
        var cansend = true;
        var errormess = '';

        jQuery('#register-inputs input').each(function() {
            switch(jQuery(this).attr('name'))
            {
                case 'name':
                    if (jQuery(this).val() == '') {
                        cansend = false;
                        errormess = errormess + '\n - Заполните поле вашего имени';
                    }
                break;
                case 'family':
                    if (jQuery(this).val() == '') {
                        cansend = false;
                        errormess = errormess + '\n - Заполните поле вашей фамилии';
                    }
                break;
            }
        });

        if (!this.correctEmail) {
            cansend = false;
            errormess = errormess + '\n - Укажите ваш действующий E-Mail.';
        }

        if (cansend)
        {
            var inEmail = $('#register-inputs input[name="email"]');
            var inName = $('#register-inputs input[name="name"]');
            var inFamily = $('#register-inputs input[name="family"]');
            var token = $('#register-inputs input[type="hidden"]');

            var senddata = {
                'email': inEmail.val(),
                'name': inName.val(),
                'family': inFamily.val()
            };

            senddata[token.attr('name')] = token.val();

            $('#register-inputs #register-loading').css('display','block');

            $('#register-inputs').css('display','none');
            $('#register-progress').css('display','block');
            $('#register-progress-text').html('Идет процесс регистрации.<br/>Пожалуйста, подождите.');
            alert('in register');
            $.ajax({
                type: "POST",
                url: '/auth/register',
                data: senddata,
                dataType: 'json',
                success: function(data) {
                    var mess = data[0];
                    if (mess.type == 'error')
                    {
                        $('#register-progress-text').html('Возникла ошибка:<br/>'+mess.data+'.<br/><a href="#" onclick="resetRegister()">Повторить регистрацию</a>');
                        //updateKeys(data[1].data,data[2].data);
                    } else if (mess.type = 'completed') {
                        Reg.closeRegister(); // Скроем окошко регистрации
                        $('#register-inputs input').val(''); // Удалим из него все введенные данные
                        Navigation.loadPage('/complete_register'); // Отредиректим на сообщение о завершении регистрации
                        //$('#register-progress-text').html('Регистрация успешно завершена!<br/><a href="/cabinet">Перейти к личному кабинету</a>');
                    }
                },
                error: function (data, status, e)
                {
                    $('#register-progress-text').html('Возникла ошибка при выполнении запроса.<br/>Пожалуйста, повторите попытку регистрации позже.');
                }
            });


            return false;
        } else {
            alert('Пожалуйста, устраните следующие ошибки:'+errormess);
            return false;
        }
    }


    this.correctEmail = false;
    this.correctEmailChars = false;
    this.check_login_obj = '';

    this.checkEmail = function() {

        // На всякий случай, сделаем еще раз проверку введенного мейла
        this.checkEmailChars();

        if (!this.correctEmailChars)
            return;

        this.clearChecks();

        var login = $('#register-inputs #input-email').val();
        $('#register-inputs #input-email-check').html('проверка...');

        if (typeof(check_login_obj) == 'object')
            this.check_login_obj.abort();

        this.check_login_obj = $.ajax({
            url : '/auth/checkemail/',
            type: "POST",
            dataType : 'json',
            data: { email : login },
            success: function(data) {
                if (data.exists == '1')
                {
                    Reg.correctEmail = false;
                    $('#register-inputs #input-email-check').html('Такой email уже есть в системе. <span onclick="Reg.forgotPassword(true)" class="href">Восстановить пароль</span>.');
                } else {
                    Reg.correctEmail = true;
                    $('#register-inputs #input-email-check').html('');
                }
            }
        });
    }

    // Очистить поля ошибок
    this.clearChecks = function()
    {
        //$('#input-email-check').html('');
        $('#input-pass-check').html('');
    }

    this.checkEmailChars = function()
    {
        this.clearChecks();

        var login = $('#register-inputs #input-email').val();

        if (login != '' && (/^.+@.+\..{2,9}$/.test(login) == true))
        {
            this.correctEmailChars = true;
            $('#input-email-check').html('');
        } else {
            $('#input-email-check').html('Некорректный E-Mail');
            this.correctEmail = false;
            this.correctEmailChars = false;
        }
    }

}