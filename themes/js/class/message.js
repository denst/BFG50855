Message				= function()							{
    return Message.implement.init();
}

Message.show		= function(url,mess)				{
    Message.implement.show(url,mess);
}
Message.refresh		= function()							{
    Message.implement.refresh();
}
Message.close		= function()							{
    Message.implement.close();
}
Message.sendData	= function(url,mass)		{
    Message.implement.sendData(url,mass);
}
Message.onPageChanged	= function()		{
    Message.implement.onPageChanged();
}
/*Message.deleteMessage = function(element) {
    Message.implement.deleteMessage(element);
}*/

Message.implement = {

    container: '#message-container',
    container2: '#message-container-2',
    text: '#message-text',

    imgLoading: '<img src="/themes/images/ajax-loader.gif" alt="Загрузка..." />',
    imgCenterLoading: '<table width="100%" height="100%"><tr><td style="vertical-align: middle; text-align: center;"><img src="/themes/images/ajax-long-loader.gif" /></td></tr></table>',
    old_url: '',	// Последний урл, который загружался
    old_mess: '',	// Последние POST-параметры, которые шли вместе с урлом
    ee:EventEmitter.getInstance(),
    onPageChanged:function(){
    },
    // Загружает и отображает контент в специальной дивке
    show: function(url,mess) {
        var $this = this;

        if (mess == undefined)
            mess = {};

        mess.showmessage = 'true';

        this.old_url  = url;
        this.old_mess = mess;
		if(Navigation.activePreview!=null)
            Navigation.activePreview.hide();
        this.text.html(this.imgCenterLoading);

        this.container.css({
            display:'block'
        });

        this.container2.css({
            display:'block'
        });

        $.ajax({
            type: "POST",
            url: url,
            data: mess,
            dataType: 'html',
            success: function(data) {
                $this.text.html(data);
                $this.ee.emit('AjaxOpComplete');
            },
            error:   function(data, status, e) {
                $this.ee.emit('AjaxOpFailed',e);
                $this.text.html('Ошибка загрузки');
            }
        });
    },

    // Обновить сообщение
    refresh: function() {
        this.show(this.old_url,this.old_mess);
    },

    close: function() {
        this.container.css({
            display:'none'
        });

        this.container2.css({
            display:'none'
        });

        this.text.html('');
    },

    handleMessage:function(msg) {
        switch(msg.type) {
            case 'nothing':
                this.close();
            break;
            case 'error':
                this.ee.emit('AjaxOpFailed',{'data':msg.data, 'status': ''});
                this.ee.emit('OperationError',msg.data);
                //$(this.text).html(msg.data);
            break;
            case 'refreshpage':
                this.close();
                Navigation.refreshPage();
            break;
            case 'reloadpage':
                Navigation.reloadFullPage(msg.data);
            break;
            case 'redirect':
                this.close();
                Navigation.loadPage(msg.data);
            break;
            case 'showmessage':
                this.show(msg.data, {});
            break;
            case 'closemessage':
                Message.close();
            break;
            case 'refreshmessage':
                Message.refresh();
            break;
            case 'completed':
                this.ee.emit('OperationCompleted',msg.data);
            break;
            case 'notice':
                this.ee.emit('OperationNotice',msg.data);
            break;
            case 'warning':
                this.ee.emit('OperationWarning',msg.data);
            break;
            case 'set_system_case':
                // Обновить токен данных
                $('#system_case').val(msg.data);
            break;
            case 'updateCategory': // Перезагрузить категорию товаров
                if (msg.data == '1') {
                    Navigation.refreshPage();
                } else {
                    $('li[data-id="'+msg.data+'"] ul.jstree').remove();
                    var span = $('li[data-id="'+msg.data+'"] span:first-child');
                    if (span.length > 0)
                    {
                        span.click().click();
                    }
                }
            break;
            case 'updateNotifies': // Перезагрузить циферки
                Core.updateNotifies();
            break;
            case 'closeRegisterBox': // Свернуть бокс регистрации или логина
                Reg.closeRegister();
                Reg.closeLoginBox();
            break;
            default:
                throw 'No handler for: '+msg.type;
            break;
        }
    },
    sendData: function(url,mass) {

        if (Core.debug == true)
            this.ee.emit('OperationNotice','Посылаем данные на: <a href="' + url + '">'+ url +'</a>');

        this.ee.emit('AjaxOpStart');
        var $this = this;
        if (mass == undefined) mass = {};
        mass.json = 'true';

        // Отошлем массив и посмотрим ответ - нормально все или нет.
        $.ajax({
            type: "POST",
            url: url,
            data: mass,
            dataType: 'json',
            success: function(data) {
                data.map(function(elem){
                    $this.handleMessage.call($this,elem);
                });
                $this.ee.emit('AjaxOpComplete');
            },
            error: function (data, status, e)
            {
                $this.ee.emit('AjaxOpFailed',{
                    data:data,
                    status:status
                });
                //jQuery($this.text).html('Ошибка отправки. ');
            }
        });
    },

    init: function () {
        var self = this;
        return self;
    }
    /*
    deleteMessage: function(element) {
        element = $(element);

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/messages/delete',
            data: {messageId: element.attr('data-id')},
            success: function(data) {

            },
            error: function(data, e) {}
        });
    }
    */
}

$(document).ready(function()
{
    Message.implement.container = jQuery(Message.implement.container);
    Message.implement.container2 = jQuery(Message.implement.container2);
    Message.implement.text = jQuery(Message.implement.text);

    var ee = EventEmitter.getInstance();

    ee.on('PageChanged',function(){
        Message.onPageChanged();
    });


    /*
    ee.on('setLoaderImage',function(el){
        alert('setLoaderImage');
        var html=$(el).html();
        ee.on('AjaxOpComplete',function(){
            $(el).html(html);
        });
        ee.on('AjaxOpFailed',function(data){
            $(el).html(data.status);
        });
        $(el).html(Message.implement.imgLoading);
    });
    */

    /* Создаем методы-хуки для отслеживания нажимаемых кнопок в интерфейсе */
    $('body').on('click','.showmessage, .senddata, .senddata-token,.additem',function() {
        var $this = $(this);

        // Предотвращаем выполнение функции, если есть некоторые обстоятельства.
        //if ($this.hasClass('save_button_container-noactive'))
        //    return; Сейчас это уже не используется

        var link = $this.attr('href');
        if (link == undefined || link == '#') link = $this.attr('data-link');
        if (link == undefined) link = document.location.href;

        var input = $this.attr('data-input');

        //Есть событие-при-загрузке Посылаем его всем слушателям.
        var event=$this.attr('data-event');

        if(event)
            ee.emit(event);

        if ($this.hasClass('senddata') || $this.hasClass('senddata-token')) {
            
            if ($this.hasClass('really') && !confirm('Действительно выполнить действие?'))
                return false;
            
            if(input) {
                Core.getInputData(input, function(data) {
                    if ($this.hasClass('senddata-token'))
                        data.system_case = CORE.token;
                    Message.sendData(link, data);
                });
            } else {
                var mess = {};
                if ($this.hasClass('senddata-token'))
                    mess.system_case = CORE.token;
                Message.sendData(link, mess);
            }
        } else {
            Message.show(link);
        }

        return false;
    });

    $('body').on('click','.showimage',function() {
        var $this = $(this);
        var link = $this.attr('href');
        if (link == undefined || link == '#') link = $this.attr('data-link');
        if (link != undefined)
            Message.show('/image/?src='+link);

        return false;
    });

    $('body').on('click','.closemessage',function() {
        Message.close();
        return false;
    });

    $('body').on('click','.refreshmessage',function() {
        Message.refresh();
        return false;
    });

    // Закрытие оповещений
    $('body').on('click','.message-error',function() {
        var $this = $(this);

        $this.css({
            'height':$this.css('height'),
            'min-height':'0px'
        });
        $this.animate({
            height: 0,
            opacity: 0
        },1000,function() {
            $this.remove();
        });
    });

});


