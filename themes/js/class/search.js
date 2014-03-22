Search = new function()	{

	this.ajaxobj = null;
	this.element = null;
	this.container = null;              // Контейнер, в который складываются результаты поиска
	this.type =  null;					// Тип поиска
	this.mode = 'single';				// Название инпута, с которым мы работаем
	this.inputName = null;
	this.setContainer = function(container){
		if (typeof(container) === 'string') this.container = $(container);
		if (typeof(container) === 'object') this.container = container;
	}
	this.setSearchType = function(type){
		this.type = type;
	}
	this.setSearchMode = function(mode){
		this.mode = mode;
	}
	this.setInputName = function(name){
		this.inputName = name;
	}
	this.selectItem = function(id,name){
		if (this.mode === 'single') {
			$('#hidden_input_'+this.inputName).val(id);
			$('#search_message_'+this.inputName).html('Выбрано: '+unescape(name));
			this.element.value = '';
			this.hideSearchContainer();
            if (this.type === 'city') {
                $.ajax({
                    type: "POST",
                    data: {'city_id':id},
                    dataType: 'json',
                    url: '/search/json_city_areas',
                    success: function(items) {
                        $('select[name="city_area"] option').remove();
                        $('select[name="city_area"]').append('<option value="">Укажите район</option>');
                        items.map(function(item){
                            $('select[name="city_area"]').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    }
                });
            }
		} else {
			var input = document.createElement('input');
			input.setAttribute('type', 'hidden');
			input.setAttribute('name',this.inputName+'[]');
			input.setAttribute('value', id);

			var removebtn = document.createElement('img');
			removebtn.setAttribute('src', '/themes/images/cross_16.png');
			removebtn.setAttribute('onclick', '$(this).parent().remove()');

			var span = document.createElement('span');
			span.innerHTML = unescape(name);

			var div = document.createElement('div');
			$(div).append(removebtn).append(span).append(input);

			$('#search_message_'+this.inputName).append(div);
			this.element.value = '';
			this.hideSearchContainer();
		}
	}

	this.search = function (element) {
		this.element = element;

        if (this.ajaxobj != null) this.ajaxobj.abort();

		if (this.ajaxobj == null || this.ajaxobj.readyState == 4 || this.ajaxobj.readyState == 0)
		{
			this.ajaxobj = jQuery.ajax({
				type: "POST",
				url: '/search/json_'+this.type,
				data: {'search':element.value},
				dataType: 'json',
				success: function(data) {
					if (data.error == 'no' || data.items == []) {

						Search.container.css({
							'display'	: 'block',
							'width'	: Search.element.clientWidth,
						});
                        
                        $(Search.element).css({
                            'margin' : 0
                        });

						Search.container.html('');
                        var empty = true;
						for (var item in data.items)
						{
                            empty = false;
							Search.container.append('<p onclick="Search.selectItem(\''
								+item+'\',\''+escape(data.items[item])+'\')">'+data.items[item]+'</p>');
						}
                        if (empty == true)
                        {
                            Search.container.html('<p>Ничего не найдено</p>');
                        }
					} else {
						Search.container.css('display','none');
					}
				},
				error: function (data, status, e)
				{
				}
			});
		}
	}

	this.hideSearchContainer = function() {
		//this.container.css({'left':'-5000px','top':'0px'});
		this.container.css('display','none');
		this.container.html('');
        
        $(Search.element).css({
            'margin' : ''
        });
        
        if (this.ajaxobj != null) this.ajaxobj.abort();
	}

    // Нажатие на кнопку "подробнее" на товаре
    this.showItemInfo = function(element) {

        var $this = $(element);
        var item_id = $this.attr('data-id');
        var container = $('#companies-selling-'+item_id);
        var td = $('td',container);

        if ($this.hasClass('show-selling-companies'))
        {
            $this.removeClass('show-selling-companies').addClass('close-selling-companies');

            //$('#search-item-'+item_id).css('display','none');
            container.css('display','');
            td.html(Navigation.imgCenterLoading);
            $.ajax({
                url: '/items/info/' + item_id,
                data: { 'mode' : 'short' },
                success: function(data) {
                    td.html(data);
                }
            });
        } else {
            $this.removeClass('close-selling-companies').addClass('show-selling-companies');
            container.css('display','none');
        }

    }

    this.closeItemInfo = function(element) {
        var $this = $(element);
        var item_id = $this.attr('data-id');
        var container = $('#companies-selling-'+item_id);
        var tr = $('td',container);

        $('#search-item-'+item_id).css('display','');
        container.css('display','none');
        tr.html('');
    }

    this.searchTimeout = null;
    this.lastSearch = null;
    this.trySearch = function(element,event) {

        if (this.lastSearch == element.value)
            return;

        this.lastSearch = element.value;

		var $this = $(element);

		var input_id = $this.attr('id');
		if (input_id === undefined || $('#search_results_'+input_id).length === 0) {
            // Если объект еще не создан, создаем его
			var random_id = getRandomInt(9999999,99999999);
			$this.attr('id',random_id);
			$this.after('<div class="search-items-container" style="display:none" id="search_results_'+random_id+'"></div>');
			input_id = random_id;
		}

		Search.setContainer('#search_results_'+input_id);

        $(element).parent().css('width',element.clientWidth); // Сделаем контейнеру "правильный" размер :)

		var searchType = $this.attr('data-search-type');
		if ($this.attr('data-multiple') != undefined) Search.setSearchMode('multi');
		else Search.setSearchMode('single');

		var inputName = $this.attr('data-input-name');
		if (inputName == undefined) return false;
		Search.setInputName(inputName);

		if (typeof(searchType) != undefined)
		{
			Search.setSearchType(searchType);
			$('#hidden_input_'+searchType).val('');
			if (Search.mode === 'single') $('#search_message_'+searchType).html('');
		}

        var keyCode = 10000;
        if (typeof(event) != 'undefined')
            keyCode = event.keyCode;

        // Если не была нажата какая-нибудь командная клавиша (типа alt, стрелок и т.д.)
        if (!(keyCode in {33 : '',34 : '',35 : '',36 : '',37 : '',38 : '',39 : '',40 : '',16 : '',17 : '',18 : '',20 : '',27 : '',116 : ''}))
        {
            if (this.searchTimeout != null)
                clearTimeout(this.searchTimeout);

			if (element.value.length > 2)
			{
                // Если был нажат Enter - запустим поиск сразу. Иначе - через пол секунды.
                if (keyCode == 13 || keyCode == 1000)
                {
                    //Search.search(element.value);
    				Search.search(element);
                } else {
                    this.searchTimeout = setTimeout(function(){
                        Search.search(element);
                    }, 500);
                }

			} else {
				Search.hideSearchContainer();
			}

        }

    }

};

$(document).ready(function() {

	$(window).click(function(e) {
		if(Search.container !== null && Search.container.css('display') !== 'none') {

			var hide = true;

			// Если кликнули на сам контейнер, не скрываем
			if ($(e.target).is(Search.container) === true) hide = false;
			else { // Если кликнули на кого-то из дочерних элементов контейнера, тоже не скрываем
				$(e.target).parentsUntil('body').each(function() {
					if ($(this).is(Search.container)) hide = false;
				});
			}

			if (hide) Search.hideSearchContainer();
		}
	});

});
