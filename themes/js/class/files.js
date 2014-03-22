Files = {
    params: {'type' : 'my'},
    addform: '',
    choseform: '',
    searchInput: '',
    init: function(choseform) {
        this.choseform = $(choseform);
        this.imagesContainer = $('#images-container',this.choseform);
        this.addform = $(this.choseform.attr('data-addform'));
        this.searchInput = $('#images-search-filter',this.choseform);
        this.loadPhotos();
    },

    filter: function(type) {
        if (type == 'my')
        {
            this.params.type = 'my';
        } else {
            this.params.type = 'all';
        }

        this.loadPhotos(this.searchInput.val());
    },

    // Загрузить картинку из УРЛа
    loadURL: function(url) {
        var $this = this;

        this.params.searchText = searchText;

        //TODO В данный момент это недописанная шляпа-копия метода loadPhotos. Надо будет потом доделать это дело..

        $.ajax({
            url: '/files/addurl',
            type: 'POST',
            data: $this.params,
            dataType: 'json',
            success: function(data) {
                $this.imagesContainer.html($.tmpl($('#tmpl-photos-list'),{'data':data}));
            },
            error: function(data, status, e) {
                $this.imagesContainer.html('Произошла ошибка при получении данных. Пожалуйста, повторите попытку позже.');
            }
        });
    },

    loadPhotos: function(searchText) {
        var $this = this;
        if (searchText == undefined) searchText = '';

        var selectedPhotos = [];
        $('input',this.addform).each(function(){
            selectedPhotos.push($(this).val());
        });

        console.log(selectedPhotos);

        this.params.selectedPhotos = selectedPhotos;
        this.params.searchText = searchText;

        $.ajax({
            url: '/search/json_publicimages/',
            type: 'POST',
            data: $this.params,
            dataType: 'json',
            success: function(data) {
                $this.imagesContainer.html($.tmpl($('#tmpl-photos-list'),{'data':data}));
            },
            error: function(data, status, e) {
                $this.imagesContainer.html('Произошла ошибка при получении данных. Пожалуйста, повторите попытку позже.');
            }
        });
    },

    selectFile: function(file_id,link,element) {
        if (element != undefined) $(element).remove();

        EventEmitter.getInstance().emit('OperationNotice','<img src="'+link+'" style="width:50px;" /> Фотография выбрана.');

        this.addform.append($.tmpl($('#tmpl-photo'),{'file_id':file_id,'link':link}));
    },

    deleteFile: function(element) {
        $(element).parent().remove();
    },

    realDeleteFile: function(file_id, element) {
        $(element).parent().remove();
        Message.sendData('/files/delete/' + file_id + '/norefresh', {confirm: 'yes', 'system_case' : Core.token()});
    },

    // Удалить файл документа
    deleteDocumentFile: function(document_id, element) {
        $(element).parent().remove();
        Message.sendData('/companies_documents/delete/' + document_id + '/norefresh', {'system_case' : Core.token()});
    }
}

var Pin = new function() {

    this.selectandclose = function(elem, fileId, fileName)
    {
        fileId = fileId || $(elem).attr('data-fileid');
        fileName = fileName || $(elem).attr('data-filename');

        Message.close();

        $('#pinedFilesList').append(
            '<div style="height:50px;"><img onclick="$(this).parent().remove()" style="float: left; margin-right: 5px; cursor: pointer;" src="/themes/images/cross_16.png">'
            + fileName + ' <input type="hidden" name="files[]" value="' + fileId + '"/> </div>');
    };

    this.clearselect = function(elem) {
        $(elem).remove();
    };
}




