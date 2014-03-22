Tip = new function() {

    this.admin = false;
    this.tips = [];
    this.init = function(tips) {
        tips.map(function(tip) {
            tip.updated = false;
        });

        this.tips = tips;
    }

    this.loadTips = function(link) {
        if (typeof(link) != 'undefined')
        {
            $('.system-tip').remove(); // Удалим все подсказки, которые были здесь

            var link = link.replace(Core.domain,'/');

            var tips = [];

            this.tips.map(function(tip){
                if (tip.link == link)
                    tips.push(tip);
            });

            var time = 300;
            var alltime = 500;
            
            tips.map(function(tip) {
                alltime += time;

                setTimeout(function() {
                    Tip.add(tip.id,tip.x,tip.y,tip.text);
                }, alltime);
            });
        }

    }

    this.remove = function(id) {
        if (!this.admin)
        {
            $('#system-tip-' + id).remove();

            Tip.tips.map(function(tip){
                if (tip.id == id)
                {
                    tip.link = '*J*($#JF#*('; // Чтобы эта подсказка больше не появлялась
                }
            });

            Message.sendData('/tips/mark/' + id);
        }
    }

    this.add = function(id,x,y,text) {
        $('body').append($.tmpl($('#t-tip'),{
            id: id,
            x: x,
            y: y,
            text: nl2br(text)
        }));

        var tip = $('#system-tip-' + id);

        tip.animate({opacity: 1},1000);

        if (this.admin)
        {
            tip.css('cursor','pointer').draggable({
                stop: function(event, ui) {
                    // Если админ переместит подсказку, обновим ее координаты в массиве подсказок
                    Tip.tips.map(function(tip) {
                        if (tip.id == id) {
                            tip.x = ui.position.left;
                            tip.y = ui.position.top;
                            tip.updated = true;
                        }
                    });
                }
            });

            var tip_text = $('.system-tip-text',tip);
            tip_text.html('<textarea onchange="Tip.updateText('+id+',$(this).val())" style="width:'+(parseInt(tip_text.width())+10)+'px; height:'+tip_text.height()+'px" name="system-tip-text-'+id+'">'+text+'</textarea>');
        }
    }

    this.updateText = function(id,text) {
        Tip.tips.map(function(tip) {

            if (tip.id == id)
            {
                tip.text = text;
                tip.updated = true;
            }

        });
    }

    // Передать новую информацию о подсказках. Метод доступен только тем, кто имеет права на управление подсказками
    this.save = function()
    {
        if (this.admin)
        {
            var tips = [];

            Tip.tips.map(function(tip){
                if (tip.updated)
                {
                    tips.push(tip);
                    tip.updated = false;
                }
            });

            Message.sendData('/tips/save', { tips : $.toJSON(tips)});
        }
    }

}

$(document).ready(function() {

    var ee = EventEmitter.getInstance();

    ee.on('PageChanged', function(link) {
        Tip.loadTips(link);
    });

});