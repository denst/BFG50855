Import = {
    step: 1, // Шаг импорта конфигурации
    stepsCount: 10, // Всего шагов

    charset: '',            // Кодировка файла
    itemrow: '',            // Номер строки, с которой начинается список товаров
    namecol: '',            // Номер колонки, в которой расположены названия товаров
    pricecol: '',           // Номер колонки, в которой расположены цены товаров
    edizmcol: '',           // Номер колонки, в которой расположены единицы измерения товаров
    desccol: '',            // Номер колонки, в которой расположены описания товаров
    typecol: '',            // Номер колонки, в которой расположены типы товаров
    catcol: '',             // Номер колонки, в которой расположены названия категорий товаров
    edizm: '',              // ИД единицы измерения, которая будет братья за основу, если в конфигурации не прописана колонка, из которой можно взять единицу измерения
    type: '',               // ИД типа товара, который будет братья за основу, если в конфигурации не прописана колонка, из которой можно взять тип товара
    configname: '',         // Название конфигурации
    category_id: '',        // Номер рубрики, в которую будут добавляться товары

    reset: function()
    {
        this.charset = '';
        this.itemrow = '';
        this.namecol = '';
        this.pricecol = '';
        this.edizmcol = '';
        this.desccol = '';
        this.typecol = '';
        this.edizm = '';
        this.type = '';
        this.configname = '';
        this.category_id = '';

        this.setStep(1);
    },

    selectCharset: function(file,charset)
    {

        $('#step-1 #file-preview').html(Navigation.imgCenterLoading);

        this.charset = charset;

        $.ajax({
            url: '/import/getcharset/' + file + '/' + charset,
            dataType: 'html',
            success: function(data)
            {
                var lines = data.split('\n');

                // Сформируем матрицу
                for(var line in lines) {
                    lines[line] = lines[line].split('|');
                };

                $('#file-preview').html($.tmpl($('#tmpl-charset-preview'),{lines:lines}));

                $('#step-1 #next-step').css('display','block');
            }
        });
    },

    updateSteps: function()
    {

        if (this.charset == '')
            this.step = 1;

        $('#create-import-configuration .step').css('display','none');
        $('#create-import-configuration #step-' + this.step).css('display','block');

        $('#create-import-configuration #step-' + this.step + ' p').css({opacity:0.1}).stop().animate({opacity:1},1000);

        $('#create-import-configuration #steps .head-step').removeClass('current');
        $('#create-import-configuration #steps #head-step-' + this.step).addClass('current');

        $('#file-preview table').removeClass('select-row').removeClass('select-col');

        // Если это выбор строки товара
        if (this.step == 2)
        {
            $('#file-preview table td').removeClass('hovered');
            $('#file-preview table').addClass('select-row');
        }

        // Если это выбор разных колонок с параметрами товара, покажем содержимое файла
        if (this.step >= 3 && this.step <= 8)
        {
            $('#file-preview table').addClass('select-col');
        }

        // Если это последние два шага, то скрываем отображение файла
        if (this.step > 8)
        {
            $('#file-preview').css('display','none');
        } else {
            $('#file-preview').css('display','block');
        }

        // Если это последний шаг
        if (this.step == this.stepsCount)
        {
            if (this.edizmcol == '')
                $('#select-edizm').css('display','block');
            else
                $('#select-edizm').css('display','none');

            if (this.typecol == '')
                $('#select-type').css('display','block');
            else
                $('#select-type').css('display','none');
        }

    },

    setStep: function(step)
    {
        if (step > this.stepsCount)
            step = this.stepsCount;

        this.step = step;

        this.updateSteps();
    },

    nextStep: function()
    {
        this.step = this.step + 1;

        if (this.step > this.stepsCount)
            this.step = this.stepsCount;

        this.updateSteps();
    },

    previousStep: function()
    {
        this.step = this.step - 1;

        if (this.step < 1)
            this.step = 1;

        this.updateSteps();
    },

    selectCategory: function(category_id,category_name)
    {
        this.category_id = category_id;
        $('.category-select-tree').remove();
        $('#tree').html($.tmpl($('#tmpl-category-selected'),{category_name:category_name}));
        this.nextStep();
    },

    createConfiguration: function() {

        var errors = '';

        if (this.configname == '')  errors = errors + '\n - задайте имя для вашей кофигурации';
        if (this.category_id == '') errors = errors + '\n - укажите рубрику товаров';
        if (this.itemrow == '')     errors = errors + '\n - укажите строку, с которой начинается список товаров';
        if (this.namecol == '')     errors = errors + '\n - укажите колонку, в которой перечисляются имена товаров';
        if (this.pricecol == '')    errors = errors + '\n - укажите колонку, в которой перечисляются цены товаров';

        if (this.catcol == '' && this.category_id == '')
            errors = errors + '\n - укажите колонку, в которой перечисляются названия рубрик. Или укажите рубрику для конфигурации';

        if (this.edizmcol == '' && this.edizm == '')
            errors = errors + '\n - укажите колонку, в которой перечисляются единицы измерения. Или укажите единицу измерения для конфигурации';

        if (this.typecol == '' && this.type == '')
            errors = errors + '\n - укажите колонку, в которой перечисляются типы товаров. Или укажите тип товара для конфигурации';

        if (errors != '')
        {
            alert('Пожалуйста, устраните следующие ошибки:' + errors);
            return;
        }

        var params = {};
        params.configname = this.configname;
        params.itemrow  = this.itemrow;
        params.namecol  = this.namecol;
        params.pricecol = this.pricecol;
        params.edizmcol = this.edizmcol;
        params.desccol  = this.desccol;
        params.typecol  = this.typecol;
        params.edizm    = this.edizm;
        params.type     = this.type;
        params.charset  = this.charset;
        params.catcol = this.catcol;
        params.category_id = this.category_id;

        Message.sendData('/import/newconfiguration/' + $('#file_id').val(), params);
    }

}

$(document).ready(function() {

    $('#file-preview').on('click','.select-row tr',function(){
        Import.itemrow = $(this).attr('num');
        Import.nextStep();

        $('#file-preview table tr').removeClass('topitemrow').removeClass('itemrow');

        $(this).addClass('itemrow');
        var num = $(this).attr('num');
        for(var i = 1; i < num; i++)
        {
            $('#file-preview table tr[num="'+i+'"]').addClass('topitemrow');
        }
    });

    $('#file-preview').on({
        'mouseenter':function(){
            var td_class = $(this).attr('class');
            $('td.' + td_class,$(this).parent().parent()).css({background:'#80CEFF','cursor':'pointer'});
        },
        'mouseleave' : function() {
            var td_class = $(this).attr('class');
            $('td.' + td_class,$(this).parent().parent()).attr('style',null);
        }
    },'.select-col td');

    $('#file-preview').on('click','.select-col td',function() {
        if (Import.step == 3)
        {
            $('#file-preview table td').removeClass('hovered');
            Import.namecol = $(this).attr('num');
        }
        else if (Import.step == 4)
            Import.pricecol = $(this).attr('num');
        else if (Import.step == 6)
            Import.catcol = $(this).attr('num');
        else if (Import.step == 6)
            Import.edizmcol = $(this).attr('num');
        else if (Import.step == 7)
            Import.desccol = $(this).attr('num');
        else if (Import.step == 8)
            Import.typecol = $(this).attr('num');

        // Подкрасим колонку нужным цветом
        var td_class = $(this).attr('class');
        $('td.' + td_class,$(this).parent().parent()).addClass('hovered');

        Import.nextStep();
    });
});
