Item = new function() {
    this.setPrice = function(company_item_id,price) {
        Message.sendData('/items/setprice/' + company_item_id + '/' + price, {system_case : Core.token()});
    };

    this.searchTimeout = null;
    this.lastSearch = null;

    this.trySearch = function(text,event) {

        if (this.lastSearch == text)
            return;

        this.lastSearch = text;

        if (typeof(event) == 'undefined')
            var keyCode = 1000;
        else
            var keyCode = event.keyCode;

        // Если не была нажата какая-нибудь командная клавиша (типа alt, стрелок и т.д.)
        if (!(keyCode in {33 : '',34 : '',35 : '',36 : '',37 : '',38 : '',39 : '',40 : '',16 : '',17 : '',18 : '',20 : '',27 : '',116 : ''}))
        {
            if (this.searchTimeout != null)
                clearTimeout(this.searchTimeout);

            $('.createnewitem,#form-newitem').css('display','none');
            $('#finded_items').html('');

            // Если был нажат Enter - запустим поиск сразу
            if (keyCode == 13 || keyCode == 1000)
            {
                Item.search(text);
            } else {
                this.searchTimeout = setTimeout(function(){
                    Item.search(text);
                }, 1000);
            }
        }
    }

    // Поиск товаров при процедуре создания товара
    this.search = function(text) {

        // Проверим поле ввода
        if (text == '')
        {
            $('#finded_items').html('Пожалуйста, укажите название вашего товара');
            return;
        }

        var container = $('#finded_items');

        container.html(Navigation.imgLoading);

        $.ajax({
            type: "POST",
            url: '/items/search',
            data: {'search' : text},
            dataType: 'json',
            success: function(data) {
                container.html($.tmpl($('#finded_items_tmpl'),{'items' : data.items}));
                if (data.items.length == 0)
                    Item.showCreateForm();
                else
                {
                    $('.createnewitem').css('display','block');
                    Item.hideCreateForm();
                }

            },
            error: function (data, status, e)
            {

            }
        });
    }

    // Отобразить форму создания нового товара
    this.showCreateForm = function(removeSearch)
    {
        if (removeSearch == true)
            $('#finded_items').html('');

        $('#form-newitem').css('display','table');
    }

    // Скрыть форму создания нового товара
    this.hideCreateForm = function()
    {
        $('#form-newitem').css('display','none');
    }


    // Добавить товар на продажу
    this.beginBuy = function(item_id,element) {

        var $el = $(element).parent();
        var price = +$('input',$el).val();

        if (price > 0)
        {
            Message.sendData('/companies/additem', {
                'item' : item_id,
                'price' : price
            });

            $el.remove();
        } else {
            Notify.message('Пожалуйста, укажите корректную цену товару', 'warning');
        }

    }

}