ad = new function() {
    
    this.initialAd = function() {
//        ad.resetTabs();
        $("#tabs li:first a").attr("id","current"); // Активируем первую закладку
        $('#countries-id').val('1');
        $("#content .tab:first").fadeIn(); // Показываем содержание первой закладки
    }
            
    this.resetTabs = function (){
        $("#content .tab").hide(); // Скрываем содержание
        $("#tabs a").attr("id",""); //Сбрасываем id      
    }
            
    this.checkTabs = function() {

        $("#tabs a").on("click",function(e) {
            var id = $(this).attr('name').replace('#tab', '');
            e.preventDefault();
            if ($(this).attr("id") == "current"){ //Определение текущейй закладки
                return       
            } else {
                ad.resetTabs();
                $(this).attr("id","current"); // Активируем текущую закладку
                $('#countries-id').val(id);
                $($(this).attr('name')).fadeIn(); // Показываем содержание текущей закладки
            }
        });
    }
            
    this.checkSaveAd = function() {

        $('#ad-save').click(function(){
            $('#AdModal').modal('hide');
        });
    }
            
    this.setDelete = function(el) {
        var el = $(el).parent().parent();
        if (el.hasClass('deletethis')) {
            el.removeClass('deletethis');
        } else {
            el.addClass('deletethis');
        }
    }
    
    this.deleteChecked = function() {

        var ads = [];
        
        $('.deletethis').each(function(){
            ads.push($(this).attr('data-id'));
        });
        
        Message.sendData('/ad/deleteitems',{
            system_case: CORE.token,
            ads: ads
        });
    }
    
    this.checkChecked = function() {
        var checkboxes = $('.delete');
        if($('#select_all').is(':checked')) {
            checkboxes.each(function() {
                $(this).attr('checked', 'checked');
                $(this).parents('.ad').addClass('deletethis');
            });
            
        } else {
            checkboxes.each(function() {
                $(this).removeAttr('checked');
                $(this).parents('.ad').removeClass('deletethis');
            });
        }
    }
    
    this.addAd = function() {
        $('#add-ad').click(function(){
            $('.ad-save').attr('data-link', '/ad/add');
            $('#ad-name').val('');
            $('#ad-code').val('');
        });
    }
    
    this.editAd = function() {
        $('.change-ad').click(function(){
            var id = $(this).attr('id');
            $('.ad-save').attr('data-link', '/ad/edit/' + id);
            $('#ad-name').val($('#ad-name-' + id).val());
            $('#ad-code').val($('#ad-code-' + id).val());
            $('#ad-position').val($('#ad-position-' + id).val());
        });
    }
};
//$(function(){
//    ad.resetTabs();
//    ad.initialAd();
//    ad.checkTabs();
////    ad.checkSaveAd();
////    ad.setDelete();
////    ad.checkChecked();
//});
$(document).ready(function()
{
    ad.resetTabs();
    ad.initialAd();
    ad.checkTabs();
});