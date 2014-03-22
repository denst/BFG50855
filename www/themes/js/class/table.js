var Table=new function(){
    var $this=this;
    this.cache={},
    /*
     * Кнопка "свернуть/развернуть" таблицу.<br/>
     * @param id - table ID;
     */
    this.addButtonExpandListener=function(id){
        if($('#btn-expand-'+id).attr('id')){
            $('#btn-expand-'+id).unbind('click');
            $('#btn-expand-'+id).click(function(){
                var type=$('#btn-expand-'+id).attr('class');
                type=type=='btn-expand'?'btn-collapse':'btn-expand';
                $('#btn-expand-'+id).attr('class',type);
                $('#table-'+id).find('tbody tr').map(function(i,el){
                    $(el).attr('style',(type == 'btn-expand' && i > 4?'display:none':null));
                });
            });
        }
    }
    //На основе полученного ID таблицы, генерируем внутренний контент.
    this.makeTable=function(data,id){
        var clazz='even',
        tmpl=null;
        $.clearTable('#table-'+id);
        $('#btn-expand-'+id).attr('class','btn-expand');
        if(data.length==0){
            switch(id){
                case 'news':
                    $('<tr><td colspan="2">Новостей нет</td></tr>').appendTo('#table-body-'+id);
                    break;
                case 'orders-in':
                    $('<tr><td colspan="5">Входящих заказов нет</td></tr>').appendTo('#table-body-'+id);
                    break;
                case 'orders-out':
                    $('<tr><td colspan="5">Исходящих заказов нет</td></tr>').appendTo('#table-body-'+id);
                    break;
                case 'calendar':
                    $('<tr><td colspan="2">Событий в календаре нет</td></tr>').appendTo('#table-body-'+id);
                    break;
            }
        }else{
            if(data.length<4)
                $('#btn-expand-'+id).attr('class','btn-expand-null');
            data.map(function(event,i){
                clazz=clazz=='even'?'odd':'even';
                if(event.id){
                    switch(id){
                        case 'orders-in':
                        case 'orders-out':
                            tmpl=$('#tmpl-orders');
                            event.data_finish=parseInt(event.data_finish);
                            break;
                        default:
                            tmpl=$('#tmpl-'+id);
                            break;
                    }

                    //Its cluge code for format  news text
                    event=id=='news'?News.handleEvent(event):event;
                    $.tmpl(tmpl,{
                        event:event,
                        clazz:clazz,
                        id:id
                    }).appendTo($('#table-body-'+id));

                    if(i>4)$('#tr-'+id+'-'+event.id).attr('style','display:none');

                }
            });
            this.addButtonExpandListener(id);
            this.appendSelectListener();
        }
    }

    /*
    * Создание таблицы заказов.
    */
    this.prepareOrdersTable=function(data,id) {
        if(!/orders-([in|out]+)/.test(id))
            return [];

        var
            type = RegExp.$1,
            page = parseInt(data.page);

        data.pages = parseInt(data.pages);
        data.page  = parseInt(data.page);

        $('#'+type+'-title-pages').text(data.page + ' из ' + data.pages);
        $('#'+type+'-btn-back, #' + type + '-btn-forvard').unbind('click');
        $('#'+type+'-btn-back').text('←');
        $('#'+type+'-btn-forvard').text('→');

        $('#'+type+'-btn-back, #'+type+'-btn-forvard').removeClass('wait_ico').bind('click',function() {
            var btn = $(this);

            if(!/btn-([back|forvard]+)/.test(btn.attr('id')))
                return [];

            switch (RegExp.$1) {
                case 'forvard':
                    page++;
                    page=(page>data.pages)?page-1:page;
                    break;
                case 'back':
                    page--;
                    page=(page<1)?1:page;
                    break;
            }

            if(page==data.page)
                return [];

            btn.addClass('wait_ico').text(' ');
            if($this.cache['page-'+type+'-'+page]) {

                $this.makeTable($this.prepareOrdersTable($this.cache['page-'+type+'-'+page],id),id);

            } else {

                $.post('/orders/' + type + '/?page='+page, {
                    json:'true'
                },function(data) {
                    $this.cache['page-' + type + '-' + page] = data;
                    $this.makeTable($this.prepareOrdersTable(data,id),id);
                },'json');

            }

        });

        return data.orders.map(function(x){
            x.type=type;
            return x;
        });
    },

    this.pInt=function(val) {
        val=isNaN(parseInt(val.innerHTML))?
            val.innerHTML.length:
            parseInt(val.innerHTML);
        return val;
    },

    this.appendSelectListener=function() {
        $('.panel-table tbody tr, table.tablesorter tbody tr').bind('click',function(event) {
            var el=$(this);
            if(el.hasClass('no-modification')){
                return;
            }else{
                $('.panel-table tbody tr, table.tablesorter .selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
    },
    /*
    * Биндимся на клик по "названию столбца" таблицы.
    * При клике на нём данный столбец будет сортироваться.
    */
    this.registerTableSorter=function(table) {

        table.find('thead tr:last-child th').each(function(i) {
            var th=$(this);
            th.unbind('click');
            th.bind('click',function() {
                var sort_type=th.attr('sort-type')==undefined||th.attr('sort-type')=='back'?'forvard':'back';
                th.attr('sort-type',sort_type);
                var trs=table.find('tbody tr:visible'),
                clazz='even';

                trs.sort(function(tr1,tr2) {

                    var val1=$(tr1).find('td:visible')[i];
                    var val2=$(tr2).find('td:visible')[i];

                    if(val2&&val1){
                        val1=$this.pInt(val1),
                        val2=$this.pInt(val2);
                        if(sort_type=='forvard'){
                            clazz='odd';
                            return val2-val1;
                        }else{
                            return val1-val2;
                        }
                    }else
                        return 0;
                });

                $.clearTable(table);

                trs.map(function(i,el) {
                    if(el&&el.innerHTML){
                        clazz=clazz=='even'?'odd':'even',
                        el=$(el);
                        el.attr('class',clazz);
                        el.appendTo(table);
                    }
                });

                $this.addButtonExpandListener(table.attr('id'));
                $this.appendSelectListener();
            });
        });
    }
};

EventEmitter.getInstance().on('PageChanged, AjaxOpComplete', function() {

    $('.tablesorter, .component-panel .panel-content table').each(function() {
        var clazz='even',
        el=$(this);
        if(!el.hasClass('no-modification')){

            el.find('tr:visible').each(function(){
                clazz=clazz=='even'?'odd':'even';
                $(this).addClass(clazz);
            });
        }
        Table.appendSelectListener();
        Table.registerTableSorter(el);
    });

    $('.panel-content').each(function(){
        var $el=$(this);
        var id=$el.attr('id');
        if($el.attr('data-link')){
            $.post($el.attr('data-link'), {
                json:'true'
            }, function(data){
                var make = false;
                switch(id)
                {
                    case 'orders-in':
                    case 'orders-out':
                        /orders-([in|out]+)/.test(id);
                        Table.cache['page-'+RegExp.$1+'-1']=data;
                        data=Table.prepareOrdersTable(data,id);
                        make = true;
                    break;
                }

                if (make) Table.makeTable(data,id);
            }, 'json');
        }else{
            return;
        }
    })
});