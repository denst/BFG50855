// Класс корзины

Card = function() {
    return Card.implement.init();
}

// Добавить товар в корзину
Card.addItem = function(company_id,item_id,price,object) {
    return Card.implement.addItem(company_id,item_id,price,object);
}

Card.clear = function() {
    Card.implement.clear();
}
Card.recalcItem = function(company_id,item_id) {
    Card.implement.recalcItem(company_id,item_id);
}
Card.deleteItem = function(company_id,item_id) {
    Card.implement.deleteItem(company_id,item_id);
}
Card.changeSource = function(source) {
    Card.implement.changeSource(source);
}
Card.updateText=function(){
    Card.implement.updateText()
    };
Card.saveObject=function(object){
    Card.implement.saveObject(object)
    };
Card.clearObject=function(){
    Card.implement.clearObject()
    };
Card.getObject=function(){
    return Card.implement.getObject()
    };
Card.implement = {
    svistInd: 0,
    init: function () {
        var self = this;
        return self;
    },
    changeSource: function(source) {
        var $link = $('#make_order');
        var order_id = $link.attr('data-order-id');
        $link.attr('data-link','/orders/add/'+order_id+'/'+source);
    },
    recalcItem: function(company_id,item_id) {
        var $count = $('input[name=item_count_'+item_id+']');
        var count = parseInt($count.val());
        if(isNaN(count)){
            $count.val(0);
            return;
        }
        var price = $('#item_price_'+item_id).html();

        var count_on_store = $count.attr('data-count');	// количество товара на складе производителя

        if (count < 0) count = 0;
        if (count > count_on_store) count = count_on_store;
        $count.val(count);

        $('#item_itogo_'+item_id).html((count * price));

        // обновим кукисы
        this.setItem(company_id,item_id,count);

        // Обновим сумму "Итого".
        var itogo_summ = 0;
        $('.item_itogo').each(function(){
            itogo_summ = itogo_summ + Number(this.innerHTML);
        });
        $('#itogo_summ').html(itogo_summ);
    },
    clear: function() {
        $.cookie('card',null);
        EventEmitter.getInstance().emit('cardClosed');
        Navigation.refreshPage();
        Core.updateNotifies();
        this.updateText();
    },
    addItem: function(company_id,item_id,price,element) {

        if (CORE.company_id == '0')
        {
            Notify.message('Вы не можете приобрести товар, так как не имеете своей компании','error');
            return;
        }

        if (company_id == CORE.company_id)
        {
            Notify.message('Вы не можете приобрести товар у своей компании','error');
            return;
        }

        var $element = $(element);

        var object = this.getObject();

        var firstItemAddedInCard = false;
        var count = 1;

        var input = $('input',$element.parent());
        var inc_count = input.val();
        if (inc_count == '' || inc_count < 1)
        {
            inc_count = 0;
            input.val('0');
            return;
        }

        inc_count = parseInt(inc_count);

        var needUpdateNotifies = false;

        if (object === null || typeof(object) != 'object')
        {
            firstItemAddedInCard = true;
            EventEmitter.getInstance().emit('OperationCompleted','Товар добавлен в <a href="/card">корзину</a>');
            needUpdateNotifies = true;

            object = new Array;
            object.push({
                'cid':company_id,
                'itms':[{
                    'id' : item_id,
                    'cnt' : inc_count,
                    'prc' : price
                }]
            });

            count = inc_count;
        } else {
            // Найдем нужную нам компанию
            var finded_company = false;
            var max_order_id = 0;

            for(var i in object)
            {
                max_order_id = i;

                // Если нашли нужную компанию, посмотрим в ней товар
                if (object[i] !== null && object[i].cid == company_id)
                {
                    finded_company = true;
                    var finded_item = false;
                    var items_length = object[i].itms.length;
                    // Найдем нужный товар
                    for (var ii = 0; ii < items_length; ii++)
                    {
                        // Если нашли товар, установим его количество закупок
                        if (object[i].itms[ii].id == item_id)
                        {
                            finded_item = true;
                            object[i].itms[ii].cnt = object[i].itms[ii].cnt + inc_count;
                            count = object[i].itms[ii].cnt;
                        }
                    }

                    // Если товар не нашли, создаем его
                    if (finded_item === false)
                    {
                        EventEmitter.getInstance().emit('OperationCompleted','Товар добавлен в <a href="/card">корзину</a>');

                        object[i].itms.push({
                            'id':item_id,
                            'cnt':inc_count,
                            'prc' : price
                        });

                        count = inc_count;
                    }
                }
            }

            // Если компанию не нашли, создаем её и добавляем в неё указанный товар
            if (finded_company === false)
            {
                EventEmitter.getInstance().emit('OperationCompleted','Товар добавлен в <a href="/card">корзину</a>');
                needUpdateNotifies = true;

                max_order_id = +max_order_id;
                object[max_order_id+1] = {
                    'cid':company_id,
                    'itms':[{
                        'id' : item_id,
                        'cnt' : inc_count,
                        'prc' : price
                    }]
                };
                count = inc_count;
            }
        }

        this.saveObject(object);
        this.updateText();

        // Если это необходимо, обновим "циферки" количества заказов в верхнем меню
        if (needUpdateNotifies)
        {
            Core.updateNotifies();
        }

        // Если был добавлен первый товар в корзину, перезагрузим левую колонку, чтобы на ней появилась кнопка "Корзина"
        if (firstItemAddedInCard)
            EventEmitter.getInstance().emit('cardOpened');

        if (element != undefined && element != null)
        {
            this.svistInd += 1;
            // Прикрутим свистелку-перделку для пущего эффекта
            var random_id = getRandomInt(1,200);
            var elementPos = getElementPosition(element);
            if (random_id < 3)
            {
                // левелап..
                $('body').append('<div class="svistelka-levelup" style="position:absolute;'
                    +'left:'+(elementPos.left+(Math.floor(element.clientWidth / 2))-60)+'px;'
                    +'top:'+(elementPos.top-10)+'px;"'
                    +'id="svistelka_'+this.svistInd+'">+1 LEVELUP</div>');
            } else {
                // Создаем строку "+1"
                $('body').append('<div class="svistelka-plus1" style="position:absolute;'
                    +'left:'+(elementPos.left+(Math.floor(element.clientWidth / 2))-7)+'px;'
                    +'top:'+(elementPos.top-10)+'px;"'
                    +'id="svistelka_'+this.svistInd+'">'+count+'</div>');
            }

            //var $svistelka = $('#svistelka_'+random_id);
            //var svistelka = document.getElementById('svistelka_'+random_id);

            $('#svistelka_'+this.svistInd).animate({
                top:'-=100',
                opacity:0
            },2000,'linear',function(){
                $(this).remove();
            });
        }
    },
    updateText:function(){
        $('#card').hide();
        var card = this.getObject();
        count=0,
        price=0;

        for(var i in card){
            var item=card[i];
            if(item!=null){
                count+=item.itms.length;
                for(var j in item.itms){
                    price+=parseInt(item.itms[j].prc)*parseInt(item.itms[j].cnt);
                }
            }
        }

        switch(count){
            case 1:
                $('#card-text').text('У вас 1 заказ. Стоимость '+price+' руб.');
                break;
            case 2:
            case 3:
            case 4:
                $('#card-text').text('У вас '+count+' заказа. Стоимость '+price+' руб.');
                break;
            default:
                $('#card-text').text('У вас '+count+' заказов. Стоимость '+price+' руб.');
                break;
        }

        if(count>0){
            $('#card').show();
        }

    },
    setItem: function(company_id,item_id,count) {

        var object = this.getObject();
        if (object === null || typeof(object) != 'object')
        {
        } else {

            // Найден нужную нам компанию
            var finded_company = false;
            for (var i  in object)
            {
                // Если нашли нужную компанию, посмотрим в ней товар
                if (object[i]!=null&&object[i].cid == company_id)
                {
                    finded_company = true;
                    var finded_item = false;
                    var items_length = object[i].itms.length;
                    // Найдем нужный товар
                    for (var ii = 0; ii < items_length; ii++)
                    {
                        // Если нашли новый товар, увеличим его количество закупок
                        if (object[i].itms[ii].id == item_id)
                        {
                            finded_item = true;
                            object[i].itms[ii].cnt = count;
                        }
                    }

                    // Если товар не нашли, создаем его
                    if (finded_item === false)
                    {
                    }
                }
            }

            // Если компанию не нашли, создаем её и добавляем её указанный товар
            if (finded_company === false)
            {
            }
        }

        this.saveObject(object);
        this.updateText();
    },
    deleteItem: function(company_id,item_id) {
        var object = this.getObject();
        if (object !== null && typeof(object) == 'object')
        {
            // Найден нужную нам компанию
            for (var i in object)
            {
                // Если нашли нужную компанию, посмотрим в ней товар
                if (object[i]!=null&&object[i].cid == company_id)
                {
                    object[i].itms.map(function(item,ii){
                        // Если нашли нужный товар, удалим его
                        if (item.id == item_id)
                            object[i].itms.splice(ii,1);
                    });
                }
            }
        }

        // Проверим, не является ли теперь пустым наш массив, и относительно этого сделаем дальнейшие шаги..
        var count = 0;
        for(var i in object){
            var item=object[i];
            if(item!=null){
                count+=item.itms.length;
            }
        }

        if(count > 0){
            this.saveObject(object);
            this.updateText();
        }
        else
        {
            this.clearObject();
            EventEmitter.getInstance().emit('cardClosed');
        }

        Core.updateNotifies();
        Navigation.loadPage('/card');

    },

    saveObject: function(object){
        $.cookie('card',$.toJSON(object),{
            expires : 7
        });
    },
    clearObject: function(){
        $.cookie('card',null);
        Navigation.loadPage('/card');
    },
    getObject: function(){
        return $.evalJSON($.cookie('card'));
    }
}
EventEmitter.getInstance().on('PageChanged', function(){
    Card.implement.updateText();
});