// Класс валидации форм

var Validation = new function(){
    /*
    * Все правила валидации засуним в хеш, ключём будет название валидатора, значением - объект валидатор.
    */
    var regulars={},
    __instance=null,
    checkElement=function(el) {

        if(!el.attr('data-validation'))
            return 'ok';
        var types=el.attr('data-validation').split(';'),
        error='',
        isError=false;
        /*
        * Перебераем все атрибуты валидации прописанные к элементу.
        */
        for(var i in types) {
            var type=types[i].toString().trim();

            /*
            * Пробуем получить дополнительные параметры валидации
            */
            if(type.match(':')){
                var param=type.split(':');
                type=param[0].toString().trim();
                param=param[1].toString().trim();
            }

            /*
             * Достаём объект-валидатор по его названию.
             */
            var regular = regulars[type];
            if(typeof(regular)!='undefined'){
                error+=regular.error.replace('{%}',param)+'</br>';
                if(!isError){
                    //<b>ПОМНИ! VALIDATOR ВЕРНЁТ TRUE ЕСЛИ ДАННЫЕ ВАЛИДНЫ</b>
                    isError=!regular.validator(el.val(),param);
                }
            }
        }
        if(isError)
            return error;
        else
            return 'ok';
    };

    Validation.getInstance=function(){
        return new Validation;
    };
    function Validation () {
        if (!__instance){
            __instance=this;
            /*
                  Объект-валидатор саздадим не описывая его класс отдельно.
                  Поле error - текст ошибки выдаваемой пользователю.
                  Поле validator - функция производящая валидацию.
                  Функция должна принимать минимум один параметр - элемент для валидации.
                  Второй, не обязательный параметр - "параметры валидации".
				  Валидатор возвращает <b>TRUE</b> если нет ошибок.
            */

            /*
              Валидатор, проверяющий элемент на "не пусто".
            */
            regulars['notempty']={
                error:'Поле не может быть пустым.',
                validator:function(val){
                    return val==null ? true : val.toString().length!=0;
                }
            };
            /*
                Валидатор проверяющий минимальное кол-во символов в элементе.
            */
            regulars['minlength']={
                error:'Не менее {%} символов.',
                validator:function(val,length){
                    return val.toString().length>=length;
                }
            };

            /*
                Валидатор проверяющий максимальное кол-во символов в элементе.
            */
            regulars['maxlength']={
                error:'Не более {%} символов.',
                validator:function(val,length){
                    return val.toString().length<=length;
                }
            };

            /*
                Валидатор, проверяющий кол-во символов в элементе.
            */
            regulars['textlength']={
                error:'Строка должна содержать {%} символов.',
                validator:function(val,length){
                    return (val.toString().length == length);
                }
            };

            /*
              Проверяет наличие доменного имени в поле ввода, может принимать несколько параметров через запятую.
              domains="http://vk.com, http://vkontakte.ru"
            */
            regulars['hasdomain']={
                error:'Адрес должен начинаться с верного домена ({%})',
                validator:function(val,domains){
                    if (val == '' || val == '(an empty string)') return true;
                    domains=domains.split(',');
                    for(var i in domains){
                        if(val.indexOf(domains[i].trim())!=-1)
                            return true;
                    }
                    return false;
                }
            };

            /*
                Валидатор, проверяющий что бы поле содержало только цифры
            */

            regulars['isnumeric']={
                error:'Поле может содержать только цифры.',
                validator:function(val){
                    if (val == '' || val == '(an empty string)') return true;
                    return /^[0-9]+$/.test(val);
                }
            };
            /*
                Проверяет корректность введённого емейла.
             */
            regulars['isemail']={
                error:'Должен быть введен корректный E-Mail',
                validator:function(val){
                    if (val == '' || val == '(an empty string)') return true;
                    return /^.+@.+\..{2,9}$/.test(val);
                }
            };

            regulars['isurl']={
                error:'Должен быть введен корректный URL-адрес сайта.',
                validator:function(val){
                    if (val == '' || val == '(an empty string)') return true;
                    return /^(http|ftp|https):\/\/.+\..{2,9}/.test(val);
                }
            };

            regulars['isdate']={
                error:'Поле содержит дату',
                validator:function(val) {
                    if (val == '' || val == '(an empty string)') return true;
                    if (!/^([0-9]{1,2})(\.|\/)([0-9]{1,2})(\.|\/)([0-9]{4,4})$/.test(val)) {
                        return false;
                    } else {
                        return (parseInt(RegExp.$1)<31&&parseInt(RegExp.$3)<12&&parseInt(RegExp.$5)<2500);
                    }
                }
            };
            regulars['isphone']={
                error:'Введён не корректный формат телефона',
                validator:function(val){
                    if (val == '' || val == '(an empty string)' || val == '(an empty string)') return true;
                    return /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/.test(val)
                }
            };

            regulars['minint']={
                error:'Минимальное вводимое число {%}',
                validator:function(val,minInt){
                    if (val == '' || val == '(an empty string)') return true;
                    return parseInt(val)>=parseInt(minInt);
                }
            };
            regulars['maxint']={
                error:'Максимальное вводимое число {%}',
                validator:function(val,minInt){
                    if (val == '' || val == '(an empty string)') return true;
                    return parseInt(val)<=parseInt(minInt);
                }
            };
            regulars['intonly']={
                error:'Можно ввести только число',
                validator:function(val){
                    if (val == '' || val == '(an empty string)') return true;
                    return /^[0-9]+$/.test(val);
                }
            };
        }
        return __instance;
    };

    Validation.prototype.validate=function(el){

        // Если это скрытый элемент, то не проверять его
        if (!$(el).is(':visible')) {
            return 'ok';
        }

        var isValid = '';
        if ($(el).hasClass('search')) {
            if ($('#hidden_input_' + $(el).attr('data-input-name')).val() == '') {
                isValid = 'error';
            } else {
                isValid = 'ok';
            }
        } else {
            isValid = checkElement(el);
        }

        var $elError=$(document.getElementById('error-'+el.attr('name')));
        if (isValid == 'ok')
        {
            el.css('border', '');
            $elError.hide();
            return 'ok';
        } else {
            el.css('border', '2px solid red');
            $elError.html(isValid).show();
            $elError.stop().css('opacity','1').animate({opacity: 0.5}, 800,'linear');

            var linkcoords = getElementPosition($(el).get(0));

            if ($.scrollTo)
                $(document).stop().scrollTo(linkcoords.top-30,300);

            return 'error';
        }
    };
    return Validation;
};

$(document).ready(function() {
    var valid = Validation.getInstance();

    $('body').on('submit','form',function() {
        var formEls=$(this).find('input[type="text"], input[type="password"], input[type="hidden"], input[type="checkbox"], select, textarea');
        for(var i=0; i<=formEls.length;i++){
            if(valid.validate($(formEls[i]))!='ok'){
                return false;
            }
        }
        return true;
    });

});
