Navigation = new function() {

    this.ajaxObj = '';			// Объект ajax-загрузки
    this.left = '#left';			// Объект левой менюшки
    this.content = '#content';	// Объект, содержащий в себе весь HTML-контент страницы

    this.cache = {};
    this.caching = false;
    this.showTimeout = null;
    this.activePreview = null;

    this.ee = EventEmitter.getInstance(); // EventEmitter Object
    this.pages = {}; // Кеш загруженных страниц

    // Картинки :)
    this.imgLoading = '<img class="loading-process" src="/themes/images/ajax-loader.gif" alt="Загрузка..." />';
    this.loading = $('<div id="preview-loading"></div>');
    this.imgCenterLoading = '<table style="width:100%;height:100%;"><tr><td style="vertical-align: middle; text-align: center;"><img src="/themes/images/ajax-long-loader.gif" /></td></tr></table>';

    // Подсветим нужные ссылки и уберем подсветку с ненужных
    this.activateButtons = function(url) {

        var checkurl = checkUrl(url,{
            'users'             : ['/users'],
            'admin'             : ['/admin'],
            'posts'             : ['/posts'],
            'pages'             : ['/page'],
            'news'              : ['/news'],
            'countries'         : ['/cities','/countries','/regions'],
            'dist'              : ['/dist'],
            'spravochnik'       : ['/spravochnik'],
            'menu'              : ['/menu'],
            'seoadmin'          : ['/seoadmin']
        });
        $('#menu a').removeClass('button-active');
        if (checkurl !== null)
            $('#menu #menu-'+checkurl).addClass('button-active');

        checkurl = checkUrl(url,{
            'flats'             : ['/kvartiry'],
            'houses'            : ['/doma','/zemli'],
            'garages'           : ['/garaji']
        });
        $('#headmenu li').removeClass('active');
        if (checkurl !== null)
            $('#head-'+checkurl).addClass('active');
    };

    this.onPageStartChanging = function(url) {
        this.activateButtons(url);
    };

    this.onNeedRefreshPage = function() {
        this.refreshPage();
    };

    this.reloadFullPage = function(link) {
        if (link == undefined || link == '') link = document.location.href;
        document.location.href = link;
    };

    this.refreshPage = function() {

        var state = null;

        if (supports_history_api() && window.history.state)
        {
            state = window.history.state.data;
        }

        this.loadPage(document.location.href,state);

    };

    // Установить новый контент на странице
    this.setContent = function(content) {
        this.content.html(content);
    };

    // Установим новую страницу
    this.setPage = function(link,mess,data,time) {

        // Положим данные в кэш
        if (this.caching)
        {
            this.pages[link] = {
                content: data,
                time: time
            };
        }

        // Расскажем Гуглу о том, что мы загрузили новую страницу
        if(typeof(_gaq) != 'undefined') {
            _gaq.push(['_trackPageview', link]);
            _gaq.push(['_trackEvent', 'Forms', 'AJAX Form']);
        }

        // Расскажем Яндекс-Метрике о том, что мы загрузили новую страницу
        if(typeof(yaCounter15212854) != 'undefined')
        {
            yaCounter15212854.hit(link);
        }

        // Если браузером поддерживается хистори, используем это
        if (supports_history_api()) {
            history.pushState({html : data, data : mess}, null, link);
        }

        this.setContent(data);

        // Оповестим всех об изменении страницы
        this.ee.emit('PageChanged',link);
        
        // Обновим счетчик ЛайвИнтернет
        updateLiveInternetCounter();

        // Если cсылка содержит еще и якорь, то направим страницу туда
        if (link.lastIndexOf('#') != -1) {
            this.scrollToAnkor(link);
        } else {
            //$(document).stop().scrollTo(210,500,'linear');
        }
    };

    /*
	 * Функция загружает контент с сервера.
	 * str      куда отправлсять запрос
	 * mess     данные, которые следует передать серверу. Необязательно.
	 * */
    this.loadPage = function(str,mess) {
        var $this = this;

        if($this.activePreview!=null){
            $this.activePreview.remove();
        }

        this.ms = new Date();
        this.secs = Math.round(this.ms.getTime() * 0.001);

        // Если уже был отправлен какой-либо запрос, отменим его
        if (typeof(this.ajaxObj) == 'object') this.ajaxObj.abort();

        // Если страница есть в кеше и она была загружена недавно, то просто достаем ее из кеша. Иначе - делаем запрос на сервер.
        if (this.caching && document.location.href !== str && this.pages[str] && (this.secs - this.pages[str].time) < 60)
        {
            // Оповестим всех о начале загрузки страницы
            this.ee.emit('PageStartChanging',str);

            $this.setPage(str,mess,this.pages[str].content, this.pages[str].time);

            if (Core.debug == true)
                this.ee.emit('OperationNotice','Берем страницу из кеша: <a href="' + str + '">'+ str +'</a>');

        } else {

            // Оповестим всех о начале загрузки страницы
            this.ee.emit('PageStartChanging',str);

            if (Core.debug == true)
                this.ee.emit('OperationNotice','Загрузка страницы: <a href="' + str + '">'+ str +'</a>');

            //Fix AJAX REQ for MS IE <9
            if($.browser.msie&&parseInt($.browser.version)<9)
                str += '?ieFix=true';

            this.ajaxObj = jQuery.ajax({
                type: "POST",
                url: str,
                data: mess,
                dataType: 'html',
                success: function(data)
                {
                    $this.setPage(str,mess,data,$this.secs);
                },
                error: function (data, status, e)
                {
                    if (e != 'abort') {
                        var errm = '<h3>Ошибка загрузки страницы</h3><p>Причина: '+e+'</p><p>Попробуйте <a href="#" onclick="Navigation.refreshPage(); return false;">обновить страницу</a></p>';
                        $this.setContent(errm);
                    }
                }
            });

        }

        if (str.lastIndexOf('#') == -1)
            return true;
        else
            return false;
    };

    this.setTitle = function(name) {
        if (typeof(CORE) != 'undefined')
            document.title = name + ' | ' + CORE.apptitle;
    };

    this.scrollToAnkor = function(str) {
        var aname = str.substring(str.lastIndexOf('#')+1);
        var link = jQuery('a[name='+aname+']').get(0);
        if (typeof link == 'object') {
            var linkcoords = getElementPosition(link);
            $(document).stop().scrollTo(linkcoords.top,300);
        }
    };

}

$(document).ready(function() {

    Navigation.left = jQuery(Navigation.left);
    Navigation.content = jQuery(Navigation.content);

    // Нажатие на ссылки
    $('body').on('click','a',function () {
        var $this = jQuery(this);
        var cleared_href = this.href.substring(0,this.href.lastIndexOf('#'));
        var cleared_lasturl = document.location.href.substring(0,document.location.href.lastIndexOf('#'));

        // Если в ссылке есть якорь, и ссылка является точно такой же, как текущая страница, то просто переместим страницу на нужный якорь
        if ((this.href.indexOf('#') != -1) && (cleared_lasturl == cleared_href))
        {
            Navigation.scrollToAnkor(this.href);
            return false;
        } else {
            if ((this.getAttribute('target') != '_blank')
                && (this.href[this.href.length-1] != '#')
                && (this.href.indexOf(Core.domain) != -1)
                && (!$this.hasClass('showmessage'))
                && (!$this.hasClass('showimage'))
                && (!$this.hasClass('senddata'))
                && (!$this.hasClass('senddata-token'))
                && (!$this.parent().hasClass('cke_button')))
            {
                if ($this.attr('onclick'))
                {
                    return false;
                } else {
                    if (Message) Message.close();

                    Navigation.loadPage(this.href);
                    return false;
                }
            }

            return true;
        }
    });

    var ee=EventEmitter.getInstance();
    // Подцепимся к получению некоторых событий системы
    ee.on('PageStartChanging',function(url){
        Navigation.onPageStartChanging(url);
    });
    ee.on('NeedRefreshPage',function(){
        Navigation.onNeedRefreshPage();
    });

    if (supports_history_api()) {
        history.replaceState({
                html : $('#content').html(),
                data : {}
            }, null, document.location.href
        );
    }

    // Оповестим всех о том, что страница загружена
    ee.emit('PageChanged',document.location.href);

    // Выкрасим кнопочки на панелях в нужный цвет :)
    Navigation.activateButtons(document.location.href);

});

function supports_history_api() {
    return !!(window.history && history.pushState);
};

// Реакция на нажатие кнопкок "назад" и "вперед" в браузере
if (supports_history_api()) {

    window.addEventListener('popstate', function(e) {

       var html = $(Navigation.content).html();
        if (supports_history_api() && window.history.state)
            html = window.history.state.html;

        Navigation.setContent(html);                        // Установим нужный контент на странице
        Navigation.ee.emit('PageChanged',document.location.href);   // Оповестим всех об изменении страницы
        Navigation.activateButtons(document.location.href);         // Активируем кнопочки на сайте

    }, false);

};

