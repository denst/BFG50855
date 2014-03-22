Gallery = new function() {

    this.setMainPhoto = function(element)
    {
        $('#photos-main').val($('input',element).val());
        $(element).stop().css('opacity','0.5').animate({'opacity': 1},400,'linear');
    }

    this.removePhoto = function(element)
    {
        $(element).parent().remove();
    }

};