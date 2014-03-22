var News=new function(){

    var tmpl= '#news-template', // Шаблон для jQuery Template
    $this=this;

    this.init = function() {
        $.post('/news/feeds', {json:'true'}, function(data){
            for(var i in data) {
                $this.appendEvent($this.handleEvent(data[i]));
            }
        }, 'json');
    }

    // Показать событие
    this.appendEvent=function(event){
        $.tmpl($(tmpl),{event:event}).appendTo('#news-content');
    };

    this.handleEvent=function(event) {
        var id=parseInt(event.events_types_id);
        switch(id) {
            case 1:
                this.companyChangePrice(event);
                break;
            case 2:
                this.companyAddPartner(event);
                break;
            case 3:
                this.companyChangeStruc(event);
                break;
            case 4:
                this.companyAddEvent(event);
                break;
            case 5:
                this.companyChangeAttributes(event);
                break;
            case 6:
                this.companyDeniedUser(event);
                break;
            default:
                break
        }
        return event;
    };

    this.companyAddEvent=function(event){
        return event.event_type_name=event.new_name+'<br/>'+event.new_text;
    };

    this.companyChangeAttributes=function(event){
        var n = event.desc.length;
        if (n > 0)
        {
            var event_text='Компания <a href="/company'+event.company_id+'">'+event.company_name+'</a> изменила реквизиты: ';
            for(i = 0; i < n; i++)
            {
                switch (event.desc[i])
                {
                    case 'inn':
                        event_text += 'ИНН ';
                        break;
                    case 'kpp':
                        event_text += 'Кпп ';
                        break;
                    case 'bank':
                        event_text += 'Банк ';
                        break;
                    case 'address_ur':
                        event_text += 'Юридический адрес ';
                        break;
                    case 'address_fakt':
                        event_text += 'Фактический адрес ';
                        break;
                    case 'address_pocht':
                        event_text += 'Почтовый адрес ';
                        break;
                    case 'city_id':
                        event_text += 'Город ';
                        break;
                    case 'url':
                        event_text += 'Адрес сайта ';
                        break;
                    case 'ogrn':
                        event_text += 'ОГРН ';
                        break;
                    case 'bik':
                        event_text += 'БИК ';
                        break;
                    case 'rs':
                        event_text += 'Расчётный счёт ';
                        break;
                    case 'ls':
                        event_text += 'Корреспондетский счёт ';
                        break;
                    case 'okpo':
                        event_text += 'ОКПО ';
                        break;
                    default:

                    break;
                }
            }
            if (event.phone_is_changed)
            {
                //console.log(event.phones);
            }
        } else {
            var event_text = '';
        }
        return event.event_type_name=event_text;
    };

    this.companyAddPartner=function(event){
        var event_text='Компания <a data-link="/company'+event.company_who_id+'"'+
        ' class="preview-company-data" href="/company'+event.company_who_id+'">'+event.company_who_name+
        '</a> добавила в партнёры компанию <a data-link="/company'+event.company_target_id+'" class="preview-company-data" href="/company'+event.company_target_id+'">'+
        event.company_target_name+'</a>';
        return event.event_type_name=event_text;
    };

    // Событие "Изменение структуры компании" - отформатировать текст в соответствии с правилами русского языка.
    this.companyChangeStruc=function(event){
        var user='Сотрудник <a class="preview-user-data" data-link="/users/preview/'+event.user_id+'" href="/id'+event.user_id+'">'+event.user_name+'</a>',
        event_text,
        evt_id=parseInt(event.type_id),
        moreOneRole=event.right.length>1;
        switch(evt_id){
            case 1:
                event_text=user+' добавлен '+(moreOneRole?'на должности ':'на должность ');
                break;
            case 2:
                event_text=user+' уволен '+(moreOneRole?'с должностей ':'с должности ');
                break;
            case 3:
                event_text=user+' сменил '+(moreOneRole?'должности ':'должность ');
                break;
            default:
                event_text=' НЕИЗВЕСТНЫЙ event_id '+evt_id;
                break;
        }
        for(var i in event.right){
            switch(parseInt(event.right[i].id)){
                case 1:
                    event_text+='Владелец ';
                    break;
                case 2:
                    event_text+='Менеджер ';
                    break;
                case 3:
                    event_text+='Управление складом ';
                    break;
                case 4:
                    event_text+='Главный бухгалтер ';
                    break;
            }
        }
        return event.event_type_name=event_text;

    };

    this.companyChangePrice=function(event){
        var event_text,
        evt_id=parseInt(event.price_type_id);

        switch(evt_id){
            case 1:
                event_text='Добавлена номенклатура из поиска';
                break;
            case 2:
                event_text='Создана новая номенклатура';
                break;
            case 3:
                event_text='Произведен импорт номенклатуры';
                break;
            case 4:
                event_text='Удалены элементы номенклатуры из прайса';
                break;
            case 5:
                event_text='Обновлены элементы номенклатуры';
                break;
            default:
                event_text=' НЕИЗВЕСТНЫЙ event_id '+evt_id;
                break;
        }
        return event.event_type_name=event_text;
    };

    this.companyDeniedUser = function(event) {
        var event_text = 'Компания <a href="/company' + event.company_id + '">' + event.company_name + '</a> отказала в приеме на работу пользователю <a href="/id' + event.user_id + '">' + event.user_name + '</a>.';
        return event.event_type_name=event_text;
    }

    return News;
}