// Класс для отображения разнообразных изображений
Documents = new function() {

    this.photos = [];
    this.current = 0; // Текущая фотография

    this.openImage = function(element) {
        $('.preloadingimg').remove(); // Удалим прошлые загружаемые изображения

        var img = $(element);
        //var loading_img = document.createElement('img');

        var loading_img = new Image();
        loading_img.onload = function() {
            // После того как изображение полностью загрузится, покажем его
            Documents.setBackground(img.attr('data-img'),img.attr('data-width'),img.attr('data-height'));
        }
        loading_img.src = img.attr('data-img');

        this.setBackground('/themes/images/ajax-loader-imgpreload.gif','128','128');
        this.openBackground();

        $('#document-saveimg').attr('href',img.attr('data-img'));
        $('#document-imagetitle').html(img.attr('data-name'));

        // Если это галерея и тут есть еще фотки, инициализируем ее
        if (img.attr('data-gallery')) {
            var gallery_photos = $('img[data-gallery="' + img.attr('data-gallery') + '"]');
            if (gallery_photos.length > 1)
            {
                this.photos = gallery_photos;

                var num = 0;
                this.photos.each(function(index){
                    if ($(this).attr('src') == img.attr('src'))
                        num = index;
                });

                this.current = num;

                this.showGalleryButtons();
            } else {
                this.photos = [];
            }
        }
    }

    this.previous = function() {
        if (this.photos.length > 1 && this.current > 0)
        {
            this.current -= 1;
            $(this.photos[this.current]).click();
        }
    }

    this.next = function() {
        if (this.photos.length > 1 && this.current < (this.photos.length - 1))
        {
            this.current += 1;
            $(this.photos[this.current]).click();
        }
    }

    // Установить определенную картинку в качестве фонового изображения
    this.setBackground = function(src,width,height) {

        if (width > $(window).width())
            width = $(window).width() - 100;

        if (height > $(window).height())
            height = $(window).height() - 60;


        $('#document-image-preview').css({
            'background-image' : 'url(' + src + ')',
            'width' : width,
            'height' : height,
            'margin-left' : -(width / 2),
            'margin-top' : -(height / 2)
        });

    }

    this.showGalleryButtons = function() {
        $('#document-nextimage, #document-prevoiusimage').css({'display':'block','opacity':''});
    }

    // Открыть фон
    this.openBackground = function() {
        $('#document-gallery-container')
            .stop().css({'display':'block'})
            .animate({'opacity' : 1}, 500);

    }

    // Свернуть фон
    this.closeBackground = function() {
        $('#document-gallery-container, #document-nextimage, #document-prevoiusimage')
        .stop().animate({'opacity':0}, 200, function() {
            $(this).css('display','none');
        });
    }

};

$(document).ready(function(){

    $('body').append('<div style="display: none; opacity: 0;" id="document-gallery-container"></div>');
    $('#document-gallery-container')
        .append('<div id="document-image-preview-background"></div>')
        .append('<div id="document-image-preview"></div>')
        .append('<div id="document-nextimage" style="display: none"></div>')
        .append('<div id="document-prevoiusimage" style="display: none"></div>')
        .append('<div id="document-imagetitle"></div>')
        .append('<a title="Сохранить документ" href="" target="_blank" id="document-saveimg"></a>');

    $('#document-image-preview-background, #document-image-preview').click(function(){
        Documents.closeBackground();
    });

    $('#document-prevoiusimage').click(function(){
        Documents.previous();
    });

    $('#document-nextimage').click(function(){
        Documents.next();
    });

});