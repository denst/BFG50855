<?

// Название проекта, которое будет отображаться везде
define('APPLICATION_TITLE','Realtynova');

// Для отладки
define('PR_NORMAL','Основной цикл программы');
define('PR_EXT','Обработка особых данных');

define('MAX_FILE_SIZE',10); // Максимальный размер загружаемого файла в мегабайтах

/**
 * Скрытое имя поля, которое следует передавать для проверки от CSRF
 */
define('TOKEN', 'system_case');

define('const',1);
define('COOKIE_FILTER','filter_options_');

define('JSONA_NOTHING','nothing');
define('JSONA_REFRESHPAGE','refreshpage');
define('JSONA_RELOADPAGE','reloadpage');
define('JSONA_REDIRECT','redirect');
define('JSONA_SHOW_MESSAGE','showmessage');
define('JSONA_ERROR','error');
define('JSONA_COMPLETED','completed');
define('JSONA_NOTICE','notice');
define('JSONA_WARNING','warning');
define('JSONA_SET_TOKEN','set_system_case');
define('JSONA_CLOSE_CALL','closecall');

define('ROLE_LOGIN',1);
define('ROLE_ADMIN',2);

define('ADVERTS_CATS_FLAT',1);
define('ADVERTS_CATS_FLAT_STR','kvartiry');
define('ADVERTS_CATS_ROOM',2);
define('ADVERTS_CATS_ROOM_STR','komnati');
define('ADVERTS_CATS_TERRAIN',3);
define('ADVERTS_CATS_TERRAIN_STR','zemli');
define('ADVERTS_CATS_GARAGE',4);
define('ADVERTS_CATS_GARAGE_STR','garaji');
define('ADVERTS_CATS_HOUSE',5);
define('ADVERTS_CATS_HOUSE_STR','doma');
define('ADVERTS_CATS_COMMERCE',6);
define('ADVERTS_CATS_COMMERCE_STR','commerce');

define('ADVERTS_TYPES_PRODAJA',1);
define('ADVERTS_TYPES_PRODAJA_STR','prodaja');
define('ADVERTS_TYPES_ARENDA',2);
define('ADVERTS_TYPES_ARENDA_STR','arenda');


define('LT_STANDART',1);        // Стандартный запрос
define('LT_SHOWMESSAGE',2);     // Показать сообщение
define('LT_LOADCONTENT',3);     // Загрузка контента в какой-то контейнер на странице