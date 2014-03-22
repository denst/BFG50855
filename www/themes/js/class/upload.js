Upload = {
    instances: [],
    settings: {
        flash_url : "/themes/js/swfupload/swfupload.swf",
        upload_url: "/files/uploadimg",
        use_query_string : true,
        file_size_limit : "10 MB",
        file_types : "*.*",
        file_types_description : "Все файлы",
        file_upload_limit : 1000,
        file_queue_limit : 1000,
        file_post_name: 'file',
        post_params: {
            'flashupload' : 'true'
        },

        // Button settings
        button_image_url: "/themes/images/components/upload/uploadnew.png",
        button_width: "119",
        button_height: "30",
        button_cursor : SWFUpload.CURSOR.HAND,
        button_placeholder_id: "spanButtonPlaceHolder",
//        button_text: '<span class="theFont">Загрузить новый</span>',
//        button_text_style: ".theFont { font-size: 14; text-align: center; }",
//        button_text_left_padding: 12,
//        button_text_top_padding: 3,

        // The event handler functions are defined in handlers.js
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_start_handler : uploadStart,
        //upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        queue_complete_handler : queueComplete	// Queue plugin event
    },

    successFunc: '',
    init: function(element_id,system_case,successFunc,mask,mask_text,button,queueCompleteFunc)
    {
        this.settings.button_placeholder_id = element_id;
        this.settings.post_params.system_case = system_case;
        this.settings.post_params.user_id = CORE.user_id;
        this.settings.upload_start_handler = uploadStart;
        this.settings.upload_complete_handler = uploadComplete;

        if (typeof(successFunc) == 'function')
        {
            this.successFunc = successFunc;
        }

        if (typeof(queueCompleteFunc) == 'function')
        {
            this.settings.queue_complete_handler = queueCompleteFunc;
        }

        if (button == 'normal')
        {
            this.settings.button_image_url = "/themes/images/upload-normal.png";
            this.settings.button_width = "137";
            this.settings.button_height = "32";
        }
        if (button == 'big')
        {
            this.settings.button_image_url = "/themes/images/upload-big.png";
            this.settings.button_width = "188";
            this.settings.button_height = "40";
        }

        if (mask != undefined && mask_text != undefined)
        {
            this.settings.file_types = mask;
            this.settings.file_types_description = mask_text;
        } else {
            this.settings.file_types = '*.*';
            this.settings.file_types_description = 'Все файлы';
        }

        var swfu = new SWFUpload(this.settings);
        this.instances.push(swfu);
    }
};

function fileQueued(file) {
    //console.log('file queued');
};

function fileDialogComplete(numFilesSelected, numFilesQueued) {
    this.startUpload();
};

function fileQueueError(file) {
    //console.log('file queue error');
};

function uploadStart(file) {
    $('.swfupload').after(Navigation.imgLoading);
    //console.log('началась загрузка файла...', file.name);
	return true;
};

function uploadProgress(file, bytesLoaded, bytesTotal) {
    //var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
    //console.log('загрузка завершена на ' + percent + '%');
};

function uploadError(file, errorCode, message) {
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			console.log("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			console.log("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			console.log("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			console.log("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			console.log("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			console.log("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Stopped");
			break;
		default:
			console.log("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        console.log(ex);
    }
}

function uploadSuccess(file, serverData) {
    $('.loading-process').remove();

    if (serverData != '-1') {
        EventEmitter.getInstance().emit('OperationNotice','Файл успешно загружен на сервер');
        if (typeof(Upload.successFunc) == 'function')
            Upload.successFunc($.evalJSON(serverData));
    } else {
        EventEmitter.getInstance().emit('OperationError','Произошла ошибка при загрузке файла');
    }
};

function uploadComplete(file) {
};

function queueComplete() {
    //EventEmitter.getInstance().emit('OperationNoticed','Загрузка фотографий завершена');
};
