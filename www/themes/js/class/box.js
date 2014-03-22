Box = new function() {

    this.background = '#box-background';
    this.container = '#box-container';

    this.open = function()
    {
        this.background.stop().css({'opacity':'0','display':'block'}).animate({'opacity':0.5},400,'linear');

        if (typeof(this.container) == 'function') this.container.remove();
        $('#content').append('<div id="box-container" style="display: block; opacity: 0;"></div><div id="box-close" onclick="Box.close();"></div>');
        this.container = $('#box-container');
        this.container.animate({'opacity':1},400,'linear');
    }

    this.openBackground = function()
    {
        this.background.stop().css({'opacity':'0','display':'block'}).animate({'opacity':0.5},400,'linear');
    }

    this.close = function()
    {
        $(Box.background).stop().animate({'opacity':0},200,'linear',function(){
            Box.background.css('display','none');
        });

        $('#box-close').remove();
        Box.container = $(Box.container);
        Box.container.stop().animate({'opacity':0},200,'linear',function() {
            Box.container.remove();
        });

        Banner.close();
        Banner.closeCall();
    }
};

$(document).ready(function()
{
    Box.background = $(Box.background);
});