
var Categories = new function(){

    this.category_id = 0; // номер текущей категории
    this.loading_mode = 'notall'; // Режим загрузки категорий. all - загружать все категории; notall - загружать только категории, в которых продается что-то.
    //this.main_category_id = 0; // номер текущей главной категории // в данный момент это пока не надо

    /*
    * Загрузить данные с сервера.
    */
    this.loadData=function(url,callback) {
        $.post(url, {
            json:'true'
        },function(data){
            callback(data)
        }, 'json')
    };

    /*
     * Создать хлебные крошки категорий
     */
    this.generateTree = function(node) {
        var catid = node.attr('data-id');
        var catname = $('a:first-child',node).html();

        //var text = '<a data-id="'+catid+'" onclick="Categories.loadCategory($(this))" href="#">'+catname+'</a>';

        var parentLi = node.parent().parent();
        if (parentLi.get(0).tagName == 'LI') {
            catname = Categories.generateTree(parentLi) + ' / <a onclick="Categories.loadCategory('+catid+'); return false;" href="#">' +  catname + '</a>';
        } else {
            // Значит мы уже в самом верху дерева
            catname = $('> .category > div.categorytext',parentLi).html() + ' / <a onclick="Categories.loadCategory('+catid+'); return false;";" href="#">' + catname + '</a>';
        }

       return catname;
    };

    /*
    * Первичная загрузка последних товаров системы
    */
    this.firstLoad = function() {
        var $this = this;

        $('#search-sections').html('');
        $('#search-filter').html('Последние товары <strong>SobNet</strong>');

        $('#table-items-search tbody').html('<tr><td style="background-color: white;" colspan="3">'+Navigation.imgCenterLoading+'</td></tr>');

        $this.loadData('/items/getlastitems/',function(data) {
            $.clearTable($('#table-items-search'));
            if(data.error=='no') {
                $.tmpl($('#t-items-search-table-row'),{items:data.items}).appendTo($('#table-items-search-body'));
                EventEmitter.getInstance().emit('PageChanged');
            }
        });

        return false;
    };

    /*
    * Загрузка товаров из категории.
    */
    this.loadCategory=function(node, page_num) {
        var $this = this;

        if (typeof(node) == 'number')
            node = $('li[data-id="'+node+'"]');

        // Генерируем хлебные крошки категории, в которой мы находимся
        $('#search-sections').html(Categories.generateTree(node));

        //$.clearTable($('#table-items-search'));
        $('#table-items-search tbody').html('<tr><td style="background-color: white;" colspan="3">'+Navigation.imgCenterLoading+'</td></tr>');

        //this.main_category_id = node.parents('.categories').attr('data-id');

        var category_id = node.attr('data-id');
        var page_num = (typeof(page_num) == 'undefined') ? 1 : page_num;
        this.category_id = category_id;

        var link = '/items/getcategoryitems/' + category_id + '?page=' + page_num;

        $this.loadData(link,function(data) {
            $.clearTable($('#table-items-search'));

            if (data.error == 'no') {

                if (data.pagination.pages > 1)
                {
                    var s = {};

                    for (var x = 1; x <= data.pagination.pages; x++)
                        s[x] = x;

                    data.pagination.pages = s;
                }

                $.tmpl($('#t-items-search-table-row'),{
                    items : data.items,
                    pagination: data.pagination,
                    category_id: category_id
                }).appendTo($('#table-items-search-body'));

                // Сгенерируем фильтрацию категории
                $('#search-filter').html('').html($.tmpl($('#t-category-filter'),{params:data.params,category_id:category_id}));
                $('#search-filter select').chosen(); // Сделаем красивыми наши селекты

                EventEmitter.getInstance().emit('PageChanged');
            }

            // Отскроллим браузер наверх
            $(document).stop().scrollTo(230,500,'linear');
        });

        return false;
    };

    this.editCategory=function(node){
        var parent = node.parent().parent();
        Message.show('/categories/edit/'+node.attr('data-id')+'/'+(parent.attr('data-id')?parent.attr('data-id'):1),{});
        return false;
    };

    this.addExpandListener=function(node){
        /*
        * Найдём спанку отвечающую за разворачивание/сварачивание дочерних ветвей и навесим на неё листенер с обработчиком.
        */
        var $this=this;

        var changeEl = $('span:first-child',node);

        changeEl.bind('click', function() {
            if(changeEl.attr('class') == 'node-no-childs')
                return;

            var type=changeEl.attr('class'),
                link = '/categories/getcategoryitems/' + node.attr('data-id');

            if (Categories.loading_mode == 'all')
                link = link + '/all';

            if(type=='node-expand'){
                var childNodes=node.children('ul:hidden');
                if(childNodes.length==0){
                    changeEl.attr('class','node-loading');
                    $this.loadData(link,function(data) {
                        $this.createNodes(node,data);
                        changeEl.attr('class','node-wrap');
                    });
                }else{
                    childNodes.attr('style',null);
                    changeEl.attr('class','node-wrap');
                }
            }else{
                node.children('ul').attr('style','display:none');
                changeEl.attr('class','node-expand');
            }
        });
    }
    /*
     * Добавляем новые узлы в дерево.
     * @param el родительский элемент узла
     * @param data данные для добавления
     * @param parent_id номер родительской категории
     *
     */
    this.createNodes=function(el,data){
        if(data.error=='no'){
            var $this=this,
            ul=$('<ul class="jstree"></ul>'),
            isUpDown=false,
            isCheckAble=false,
            isClickAble=false,
            functions=[],
            treeAttrs='';
            if(el.attr('data-tree-attrs'))
                treeAttrs=el.attr('data-tree-attrs');
            /*
             * Ветка дерева может иметь дополнительные атрибуты.
             * Выдернем их, если они есть и подготовим для применения на ветви.
             */
            treeAttrs.split(';').map(function(attr,i){
                var param;
                if(attr.indexOf(':')!=-1){
                    attr=attr.split(':');
                    param=attr[1];
                    attr=attr[0];
                }
                switch(attr){
                    case 'upDown':
                        isUpDown = true;
                    break;
                    case 'checkable':
                        isCheckAble=true;
                    break;
                    case 'clickable':
                        isClickAble=true;
                        switch(param){
                            case 'editCategory':
                                functions.push($this.editCategory);
                            break;
                            case 'loadCategory':
                                functions.push($this.loadCategory);
                            break;
                        }
                    break;
                }
            });

            /*
             * Перебираем данные, строим ветку
             */
            data.items.map(function(item,i) {
                var node=$.tmpl($('#t-tree-branch'),{
                    item:item,
                    isCheckAble:isCheckAble,
                    isClickAble:isClickAble,
                    isUpDown:isUpDown,
                    data_link:'/categories/getcategoryitems/'
                }).appendTo(ul).attr('data-tree-attrs',treeAttrs);
                /*
                 * Если есть функции срабатывающие при клике, биндим на click функцию.
                 */
                functions.map(function(f,i){
                    var a=$('a:first-child',node);
                    a.bind('click',function(){
                        return f.call($this,node);
                    });
                });
                $this.addExpandListener(node);
            });

            ul.appendTo(el);
        }
    };

    this.filter = function(category_id,param_id,param_value) {
		var object = $.evalJSON($.cookie('categories_filter'));

        if (object === null || typeof(object) != 'object')
        {
            object = new Array;
            object.push({
                'cid':category_id,
                'params':{ }
            });
            object[0]['params'][param_id] = param_value;
        } else {
            // Найдем нужную нам компанию
            var finded_category = false;
            var max_order_id = 0;

            for(var i in object)
            {
                max_order_id = i;

                // Если нашли нужную категорию, поместим туда свой параметр
                if (object[i] !== null && object[i].cid == category_id)
                {
                    finded_category = true;
                    if (param_value == '')
                        delete object[i].params[param_id];
                    else
                        object[i].params[param_id] = param_value;
                }
            }

            // Если категорию не нашли, создаем её и добавляем в неё указанный параметр со значением
            if (finded_category === false)
            {
                max_order_id = +max_order_id;

                object.push({
                    'cid':category_id,
                    'params':{ }
                });
                object[max_order_id+1]['params'][param_id] = param_value;
            }
        }

        $.cookie('categories_filter',$.toJSON(object));

        var cat = $('.categories li[data-id="'+this.category_id+'"] a');
        if (cat.length > 0)
            cat[0].click();
    }

    // Скрыть все категории, чтобы не мешали
    this.hideCategories = function() {
        var cont = $('#categories_container .showed');
        cont.removeClass('showed').addClass('closed');
        cont.each(function(){
            $('.jstree',$(this).parent()).css('display','none');
        });
    }

    // Искать товары в системе
    this.searchItems = function(search) {
        // Генерируем хлебные крошки категории, в которой мы находимся
        $('#search-sections').html('Поиск товаров в системе по слову "'+search+'"');

        $('#table-items-search tbody').html('<tr><td style="background-color: white;" colspan="3">'+Navigation.imgCenterLoading+'</td></tr>');

        $.ajax({
            data: {json: true, search: search},
            type: 'POST',
            url: '/search/json_item/full',
            dataType: 'json',
            success: function(data) {
                $.clearTable($('#table-items-search'));

                $.tmpl($('#t-items-search-table-row'),{items:data.items}).appendTo($('#table-items-search-body'));

                // Сгенерируем фильтрацию категории
                $('#search-filter').html('Параметры фильтрации недоступны');

                EventEmitter.getInstance().emit('PageChanged');

                // Отскроллим браузер наверх
                $(document).stop().scrollTo(190,500,'linear');
            }
        });

        return false;
    }
};


EventEmitter.getInstance().on('PageChanged, AjaxOpComplete', function() {
    $('.jstree-container').each(function() {
        var el=$(this);
        if(el.find('li:visible').length==0) {
            var item_id = el.attr('data-id');
            var link = '/categories/getcategoryitems/' + item_id;

            Categories.loading_mode = 'notall';

            // Если среди атрибутов будет "allcats", загружаем все категории.
            var treeAttrs = '';if(el.attr('data-tree-attrs')) treeAttrs = el.attr('data-tree-attrs');
            treeAttrs.split(';').map(function(attr,i){
                if (attr == 'allcats')
                {
                    link = link + '/all';
                    Categories.loading_mode = 'all';
                }
            });

            Categories.loadData(link,function(data) {
                Categories.createNodes(el,data);
            });
        }
    });
});

$(document).ready(function() {

    // Загрузка закрытых категорий в поиске товаров
    $('#content').on('click','.closed',function() {
        var el = $(this);
        var parent_el = $(this).parent();

        var jstree = $('.jstree',parent_el);

        if (jstree.length > 0)
        {
            Categories.hideCategories();
            jstree.css('display','block');
            el.removeClass('closed');
            el.addClass('showed');
        } else {
            var item_id = parent_el.attr('data-id');

            var link = '/categories/getcategoryitems/' + item_id;
            if (Categories.loading_mode == 'all')
                link = link + '/all';

            Categories.loadData(link,function(data){
                Categories.hideCategories();
                Categories.createNodes(parent_el,data);
                el.removeClass('closed');
                el.addClass('showed');
            });
        }
    });

    // Скрытие открытых категорий в списке товаров
    $('#content').on('click','.showed',function() {
        //if ($(this).parents('.categories').attr('data-id') == Categories.main_category_id)
        //{
        //    Notify.message('Это активная категория, невозможно свернуть её.', 'notice');
        //} else {
            var el = $(this);
            var parent_el = $(this).parent();
            $('.jstree',parent_el).css('display','none');
            el.addClass('closed');
            el.removeClass('showed');
        //}
    });


});

