function resetTabs(){
    $("#content div").hide(); //Скрываем содержание
    $("#tabs a").attr("id",""); //Сбрасываем id
}

var myUrl = window.location.href; //Получаем URL
var myUrlTab = myUrl.substring(myUrl.indexOf("#")); // Для localhost/tabs.html#tab2 myUrlTab = #tab2
var myUrlTabName = myUrlTab.substring(0,4); // Для выше привденного примера myUrlTabName = #tab

(function(){
    $("#content div").hide(); // Скрываем все содержание при инициализации
    $("#tabs li:first a").attr("id","current"); // Активируем первую закладку
    $("#content div:first").fadeIn(); // Показываем содержание первой закладки

    $("#tabs a").on("click",function(e) {
        e.preventDefault();
        if ($(this).attr("id") == "current"){ //Определение текущейй закладки
            return
        }
        else{
            resetTabs();
            $(this).attr("id","current"); // Активируем текущую закладку
            $($(this).attr('name')).fadeIn(); // Показываем содержание текущей закладки
        }
    });

    for (i = 1; i <= $("#tabs li").length; i++) {
        if (myUrlTab == myUrlTabName + i) {
            resetTabs();
            $("a[name='"+myUrlTab+"']").attr("id","current"); // Активируем закладку по url
            $(myUrlTab).fadeIn(); // Показываем содержание закладки
        }
    }
})()

jQuery(document).ready(function(){

    jQuery(".niceCheck").mousedown(
        /* при клике на чекбоксе меняем его вид и значение */
        function() {

            changeCheck(jQuery(this));

        });


    jQuery(".niceCheck").each(
        /* при загрузке страницы нужно проверить какое значение имеет чекбокс и в соответствии с ним выставить вид */
        function() {

            changeCheckStart(jQuery(this));

        });

});

function changeCheck(el)
/*
            функция смены вида и значения чекбокса
            el - span контейнер дял обычного чекбокса
            input - чекбокс
             */
{
    var el = el,
    input = el.find("input").eq(0);
    if(!input.attr("checked")) {
        el.css("background-position","0 -19px");
        input.attr("checked", true)
    } else {
        el.css("background-position","0 0");
        input.attr("checked", false)
    }
    return true;
}

function changeCheckStart(el)
/*
            если установлен атрибут checked, меняем вид чекбокса
             */
{
    var el = el,
    input = el.find("input").eq(0);
    if(input.attr("checked")) {
        el.css("background-position","0 -19px");
    }
    return true;
}

