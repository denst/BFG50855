Maps = new function(){

    // Создает карту с балуном, в котором расписана информация о компании
    this.company = function(points, element)
    {
        ymaps.ready(function () {

            var map = new ymaps.Map(element, {
                // Центр карты
                center: Maps._getCenter(points),
                // Коэффициент масштабирования
                zoom: 14,
                // Тип карты
                type: "yandex#map",

                behaviors: ['drag','scrollZoom']
            });

            Maps._placemarks = [];
            Maps.map = map;

            map.controls
                .add("zoomControl")
                .add("mapTools")
                .add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));

            var x = 0;
            points.map(function(point){
                x += 1;
                // Создание метки
                var placemark = new ymaps.Placemark(
                    // Координаты метки
                    [point.x,point.y], {
                        hintContent: "",
                        iconContent: x,
                        balloonContent: (point.addr != null) ? nl2br(point.text) + '<br/><br/><p class="smalltip">' + point.addr + '</p>' : nl2br(point.text)
                    }, {
                        draggable: false,
                        hideIconOnBallon: true
                    }
                );

                // Добавление метки на карту
                map.geoObjects.add(placemark);

                // Добавим метку в массив меток
                Maps._placemarks.push(placemark);

            });

            if (points.length > 1)
                map.setBounds(Maps._getBounds(points));

        });
    }

    this._getCenter = function(points)
    {
        if (points.length == 0)
        {
            // По умолчанию установим центром Уфу
            return ['54.73453296473352', '55.97780695072327'];
        } else {
            var min_x = 999999999999999;
            var max_x = 0;
            var min_y = 999999999999999;
            var max_y = 0;

            points.map(function(point) {
                point.x = parseFloat(point.x);
                point.y = parseFloat(point.y);

                if (min_x > point.x) min_x = point.x;
                if (min_y > point.y) min_y = point.y;

                if (max_x < point.x) max_x = point.x;
                if (max_y < point.y) max_y = point.y;
            });

            var mid_x = min_x + ((max_x - min_x) / 2);
            var mid_y = min_y + ((max_y - min_y) / 2);

            return [mid_x, mid_y];
        }
    }

    this._getBounds = function(points)
    {
        if (points.length == 0)
        {
            // По умолчанию установим центром Уфу
            return [[54.67657625412595, 55.82953846435543], [54.815603893851296, 56.162561535644485]];
        } else {
            var min_x = 999999999999999;
            var max_x = 0;
            var min_y = 999999999999999;
            var max_y = 0;

            points.map(function(point) {
                point.x = parseFloat(point.x);
                point.y = parseFloat(point.y);

                if (min_x > point.x) min_x = point.x;
                if (min_y > point.y) min_y = point.y;

                if (max_x < point.x) max_x = point.x;
                if (max_y < point.y) max_y = point.y;
            });

            return [ [min_x, min_y], [max_x, max_y]];
        }
    }

    this.map = null;                // Объект карты
    this._placemarks = [];          // Массив меток на карте
    this._pointsContainer = null;   // Контейнер для управляющих меткой элементов

    // Обновляет координаты у инпутов в панели управления точками
    this.setPlacementString = function(geoPoint, num) {
        $('#coord_'+num+' input[name="points_x"]').val(geoPoint[0]);
        $('#coord_'+num+' input[name="points_y"]').val(geoPoint[1]);
    }

    // Установить текст метки
    this.setMarkText = function(text,num)
    {
        Maps._placemarks[num].properties.set('balloonContent',nl2br(text))
    }


    // Добавить на карту новую метку
    this.addPlacemark = function(point)
    {
        if (typeof(point) == 'undefined')
        {
            var center = Maps.map.getCenter();
            point = {
                x: center[0],
                y: center[1],
                text: 'Новая метка',
                city: {
                    id: 1,
                    name: 'Уфа'
                },
                addr: ''
            };
        }

        var num = this._placemarks.length;

        // Создадим инпуты для управления меткой
        this._pointsContainer.append($.tmpl($('#tmpl-coord'),{point : point, num : num}));

        // Включим поиск по городам
        $('#city-select-'+num).chosen().ajaxChosen({
            url: '/search/json_city',
            dataType: 'json',
            jsonTermKey: 'search'
        }, function (data) {

            var terms = {};

            $.each(data.items, function (i, val) {
                terms[i] = val;
            });

            return terms;
        });

        var placemark = new ymaps.Placemark(
            // Координаты метки
            [point.x,point.y], {
                hintContent: "",
                iconContent: num+1,
                balloonContent: nl2br(point.text)
            }, {
                draggable: true,
                hideIconOnBallon: false
            }
        );

        // Добавление метки на карту
        Maps.map.geoObjects.add(placemark);

        placemark.events.add('dragend',function(e) {
            var geoPoint = e.get('target').geometry.getCoordinates();
            //var zoom = e.originalEvent.target.getMap()._zoom;
            Maps.setPlacementString(geoPoint,num);
        });

        // Добавим метку в массив меток
        Maps._placemarks.push(placemark);

    }

    // Удалить метку
    this.removePlacemark = function(num)
    {
        this.map.geoObjects.remove(this._placemarks[num]);
        this._placemarks[num] = null;
        $('#coord_' + num).remove();
    }

    // Найти адрес через геокодирование и переместить туда точку, а заодно и карту)
    this.searchTimeout = null;
    this.lastSearch = null;
    this.searchLocation = function(text,num)
    {
        var Geocoder = ymaps.geocode(text, {
            results: 1,
            kind: 'house'
            //json: true
        });

        $('#search-location-'+num).html('Ищем место на карте..');

        Geocoder.then(
            function (res) {

                var coords = [];
                res.geoObjects.each(function(el) {
                    coords = el.geometry.getCoordinates();
                });

                if (coords.length > 0) {
                    //Maps._placemarks[num].geometry.setCoordinates([coords[0],coords[1]]);
                    Maps.setPlacementString(coords,num);

                    //Maps.map.setCenter(coords,14);
                    //Maps.map.geoObjects.add(res.geoObjects);

                    $('#search-location-'+num).html('Адрес успешно найден на карте!');
                } else {
                    $('#search-location-'+num).html('Место не найдено на карте :(');
                }
            },
            function (err) {
                $('#search-location-'+num).html('Ошибка при поиске на карте.');
                // обработка ошибки
            }
        );

    }
    this.trySearchLocation = function(text,num,event) {

        $('#search-location-'+num).html('');

        var text = $('#search_message_cities_id').text().replace('Выбрано: ','') + ' ' + text;

        if (this.lastSearch == text)
            return;

        this.lastSearch = text;

        var keyCode = 10000;
        if (typeof(event) != 'undefined')
            keyCode = event.keyCode;

        // Если не была нажата какая-нибудь командная клавиша (типа alt, стрелок и т.д.)
        if (!(keyCode in {33 : '',34 : '',35 : '',36 : '',37 : '',38 : '',39 : '',40 : '',16 : '',17 : '',18 : '',20 : '',27 : '',116 : ''}))
        {
            if (this.searchTimeout != null)
                clearTimeout(this.searchTimeout);

			if (text.length > 3)
			{
                // Если был нажат Enter - запустим поиск сразу. Иначе - через пол секунды.
                if (keyCode == 13 || keyCode == 1000)
                {
                    this.searchLocation(text,num);
                } else {
                    this.searchTimeout = setTimeout(function(){
                        Maps.searchLocation(text,num);
                    }, 500);
                }

			} else {
			}
        }
    }

    // Инициализация механизма редактирования точек на карте
    this.edit = function(points,pointsContainer)
    {

        var $this = this;

        this._pointsContainer = $(pointsContainer);

        ymaps.ready(function() {

            var map = new ymaps.Map("ymaps", {
                center: $this._getCenter(points),
                zoom: '11',
                type: "yandex#map"
            });

            // Обновим и обнулим данные в объекте
            $this._placemarks = [];
            $this.map = map;

            map.controls
                .add("zoomControl")
                .add("mapTools")
                .add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));

            points.map(function(point){
                $this.addPlacemark(point);
            });

            map.setBounds($this._getBounds(points));

        // При щелчке на карте показывается балун со значениями координат в месте клика
        // Надо переделать функцию, чтобы устанавливалось положение последней метки, либо вообще ее отключить
//        Maps.map.events.add("click", function(e) {
//
//            var geoPoint = e.get("coordPosition");
//            var zoom = e.getMap()._zoom;
//
//            Maps.setPlacementString(geoPoint, zoom);
//
//            placemark.geometry.setCoordinates(geoPoint);
//
//        });


        });

    }

};