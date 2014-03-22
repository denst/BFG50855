Core = new function(){

    this.domain = document.location.protocol + '//' + document.location.hostname + '/';

    this.getInputData=function(element,success,fail){
        if (element != undefined && element != ''){
            var mass = {},
            elements = $('input[type="text"], input[type="password"], input[type="hidden"], input[type="checkbox"], input[type="radio"]:checked, select, textarea',element);

            var valid = true;
            for(var i = 0; i <= elements.length;i++){

                var $this = $(elements[i]),
                    val = undefined;

                // Имя есть - обрабатываем.
                if ($this.attr('name') != undefined)
                {
                    var isValid = Validation.getInstance().validate($this);
                    if (isValid != 'ok') {
                        valid = false;
                    }

                    // Если имя имеет [] то шлём серверу массив.
                    if($this.attr('name').indexOf('[]') != -1 && $this.get(0).tagName != 'SELECT') {
                        //Если массив для хранения выбранных елементов не создан создадим его.
                        if(mass[$this.attr('name')]==undefined) {
                            mass[$this.attr('name')]=[];
                        }
                    }

                    if($this.attr('type') == 'checkbox' && $this.is(':checked')) {
                        val = $this.val();
                    } else if($this.attr('type') != 'checkbox') {
                        val = $this.val();
                    }

                    // Если это textarea и к ней прикреплен редактор - получим его код
                    if ($this.get(0).tagName == 'TEXTAREA')
                    {
                        var red = $this.data('redactor');
                        if (red != undefined)
                        {
                            val = red.getCode();
                        }

                        if (CKEDITOR.instances[$this.attr('name')]) {
                            val = CKEDITOR.instances[$this.attr('name')].getData();
                        }
                    }

                    if(typeof(mass[$this.attr('name')]) == 'object' && val != undefined) {
                        mass[$this.attr('name')].push(val);
                    }else if(val!=undefined) {
                        mass[$this.attr('name')]=val;
                    }
                }
            }

            if (valid) {
                success(mass);
            } else {
                if (fail) fail(isValid);
            }
        }
    };

    this.setCompany=function(element){
        var $this = $(element);

        $.cookie('active_company',$this.val());

        Navigation.reloadFullPage('/company' + $this.val() + '/info');
    };

    /* Создать редактор на элементе */
    this.editor = function(element,toolbar) {
        if (toolbar == undefined) toolbar = 'default';
        $(element).redactor({
            'resize' : true,
            //'autoresize' : true,
            'toolbar' : toolbar,
            'fixed' : true,
            'lang' : 'ru',
            'path': '/themes/js/redactor'
        });
    };

    // Когда пользователь навел мышь на непрочитанное сообщение, вызывается этот метод
    this.readMessage = function(element) {
        var $this = $(element);
        $this.attr('onmouseover','');
        $.ajax({
            url: '/messages/readed/' + $this.attr('data-id'),
            dataType : "json",
            success: function (data, textStatus) {
                Core.updateNotifies();
                $this.removeClass('notreaded');
                $this.unbind('hover');
            }
        })
    };

    this.token = function()
    {
        return $('#system_case').val();
    };

    this.debug = false;

};

$(document).ready(function()
{
    $.extend({
        clearTable:function(table){
            $(table).find('tbody tr').remove();
        }
    });

    // Предзагрузим некоторые картинки
    preload([
        '/themes/images/tree.png',
        '/themes/images/ajax-loader-imgpreload.gif',
        '/themes/images/ajax-loader-clear.gif',
        '/themes/images/ajax-long-loader.gif',
        '/themes/images/ajax-loader.gif'
    ]);

    // Про посылке ajax-запроса и получении включать и выключать соответствующее окошко анимации
    $("body").ajaxSend(function(event, xhr, options) {
        $('#loading').stop().css({display: 'block'}).animate({'opacity':1},300,'linear');
    }).ajaxStop(function() {
        $('#loading').stop().animate({'opacity':0},300,'linear', function() { $(this).css('display','none') });
    });

    $('body').on('click','.really',function(){
        if ($(this).hasClass('senddata') || $(this).hasClass('senddata-token'))
            return;

        if (!confirm('Действительно выполнить действие?'))
            return false;
    });

    $('body').on('click','.loadcontent',function(){
        var $this = $(this);

        var container = $('#loadcontent-container');

        $.ajax({
            type: "POST",
            url: $this.attr('data-link'),
            data: { loadcontent: 'true' },
            dataType: 'html',
            success: function(data)
            {
                container.html(data);
                container.css('display','block');
            },
            error: function (data, status, e)
            {
                if (e != 'abort') {
                    var errm = '<h3>Ошибка загрузки объекта</h3><p>Причина: '+e+'</p><p>Попробуйте <a href="#" onclick="Navigation.reloadFullPage(); return false;">обновить страницу</a></p>';
                    container.html(errm);
                }
            }
        });
    })

    $('body').on('click','.setactive',function(){
        var $this = $(this);
        var table = $this.parent().parent().parent();
        var tr = $this.parent().parent();

        $('tr',table).removeClass('active');
        tr.addClass('active');
    })

    $('body').on('click','.closecontent',function(){
        $('#loadcontent-container').css('display','none').html('');
    });

});

// Вывести информацию
function log(text)
{
    console.log(text);
    if (Core.debug)
    {
        Notify.message('Отладка: "' + text + '"', 'notice');
    }
}

function onEnter(ev, handler)
{
    ev = ev || window.event;

    if (ev.keyCode == 13) {
        if (typeof(handler) !== 'function')
            return true;

        handler();
    }

    return false;
}

function onCtrlEnter(ev, handler)
{
    ev = ev || window.event;
    if (ev.keyCode == 10 || ev.ctrlKey && ev.keyCode == 13) {
        handler();
    }
}

function onEnter(ev, handler)
{
    ev = ev || window.event;
    if (ev.keyCode == 13) {
        handler();
    }
}

function getRandomInt(min, max)
{
    // использование Math.round() даст неравномерное распределение!
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Узнать координаты элемента
function getElementPosition(elem)
{
    //var elem = document.getElementById(elemId);

    var w = elem.offsetWidth;
    var h = elem.offsetHeight;

    var l = 0;
    var t = 0;

    while (elem)
    {
        l += elem.offsetLeft;
        t += elem.offsetTop;
        elem = elem.offsetParent;
    }

    return {
        "left":l,
        "top":t,
        "width": w,
        "height":h
    };
}

// Функция получает ассоциативный массив и превращает его в get-сообщение
function ArrayToGet(mass)
{
    var s = '?';
    var x = false;

    for (var item in mass) {
        if (x == false) {
            s = s + item + '=' + mass[item];
            x = true;
        } else {
            s = s + '&' + item + '=' + mass[item];
        }
    }

    return s;
}

/* Функция проверяет массив урлов на соответствие, если найдет соответствие - то возвращает этот урл
* Используется в классе навигации для определения текущей ссылки.
*/
function checkUrl(url,strs)
{

    for (var key in strs)
    {
        for (var url_key in strs[key])
        {
            if (!!(url.indexOf('http://' + document.domain + strs[key][url_key]) + 1))
                return key;
        }
    }

    return null;
}

//Check for missing functions at first. DONT move this to doc.ready!
//Stupid IE 8-9 HAS NO Array.map (Like PS3 gamers HAS NO GAMES).
if (!Array.prototype.map)
{
    Array.prototype.map = function(fun /*, thisp*/)
    {
        var len = this.length;
        if (typeof fun != "function")
            throw new TypeError();

        var res = new Array(len);
        var thisp = arguments[1];
        for (var i = 0; i < len; i++)
        {
            if (i in this)
                res[i] = fun.call(thisp, this[i], i, this);
        }

        return res;
    };
}
//IE HAS NO Strin.trim
if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '');
    }
}

// Предзагрузка изображений
function preload(images) {
    if (typeof document.body == "undefined") return;
    try {
        var div = document.createElement("div");
        var s = div.style;
        s.position = "absolute";
        s.top = s.left = 0;
        s.visibility = "hidden";
        document.body.appendChild(div);
        div.innerHTML = "<img src=\"" + images.join("\" /><img src=\"") + "\" />";
    } catch(e) {
        // Error. Do nothing.
    }
}

function nl2br(str) {
	return str.replace(/([^>])\n/g, '$1<br/>');
}

function updateLiveInternetCounter()
{
 var liCounter = new Image(1,1);
 liCounter.src = '//counter.yadro.ru/hit?r='+
 ((typeof(screen)=='undefined')?'':';s'+screen.width+
 '*'+screen.height+'*'+(screen.colorDepth?screen.colorDepth:
 screen.pixelDepth))+';u'+escape(document.URL)+
 ';h'+escape(document.title.substring(0,80))+';'+Math.random();
}

function setActiveCountry(country_id) {
    $('#countries-list h1').removeClass('active');
    $('#countries-list .country-' + country_id).addClass('active');
    $('#cities-container .regions').removeClass('active');
    $('#regions-' + country_id).addClass('active');
}