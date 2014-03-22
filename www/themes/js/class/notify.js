Notify = {
    container: '#notifies',     // Контейнер оповещений
    template: '#t-notify',      // Шаблон оповещений
    timeout: 10,                // Количество секунд, которые будут отображаться сообщения
    ids: 0,                     // Идентификаторы оповещалок
    types: {
        'error' : {
            'icon' : 'error.png',
            'text' : 'Произошла ошибка'
        },
        'notice' : {
            'icon' : 'notice.png',
            'text' : 'Оповещение'
        },
        'warning' : {
            'icon' : 'warning.png',
            'text' : 'Внимание!'
        },
        'completed' : {
            'icon' : 'completed.png',
            'text' : 'Операция завершена'
        }
    },
    message: function(text,type) {
        this.ids += 1;

        if (!this.types[type])
            type = 'notice';

        $.tmpl(this.template,{'id': this.ids, 'text':text, 'type' : this.types[type]}).appendTo(this.container);

        var $this = this;
        var id = $this.ids;

        $('#notify-' + id).animate({opacity:1},300,'linear');
        setTimeout(function(){
            var notify = $('#notify-' + id);
            if (notify.length > 0)
            {
                notify.stop()
                    .css({'overflow':'hidden','height':notify.get(0).clientHeight})
                    .animate({'height': 0, 'opacity':'0', 'marginBottom': '-12px'},2000,'easeInOutQuart',function(){
                        $('#notify-' + id).remove();
                    });
            }
        },this.timeout*1000);
    }
};

$(document).ready(function() {

    Notify.container = $(Notify.container);
    Notify.template = $(Notify.template);

    var ee = EventEmitter.getInstance();

    ee.on('OperationCompleted',function(data) {
        Notify.message(data, 'completed');
    });
    ee.on('OperationError',function(data) {
        Notify.message(data, 'error');
    });
    ee.on('OperationNotice',function(data) {
        Notify.message(data, 'notice');
    });
    ee.on('OperationWarning',function(data) {
        Notify.message(data, 'warning');
    });

});