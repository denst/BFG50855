<? defined('SYSPATH') or die('No direct script access.');

abstract class Template extends Controller_Template {

    /**
     * Шаблон подключаемых JS и CSS файлов
     * @var Document
     */
    protected $document_template = 'main';

    /**
     * Класс пользователя
     * @var user
     */
    protected $auth;  // Объект авторизации

    protected $location; // Объект с информацией о текущей локации

    /**
     * Модель юзера, null если не авторизован
     * @var model_user
     */
    protected $user;  // Объект модели пользователя

    protected $json_error = false; // Флаг, который переводится в True, если в где-то в коде было вызвано SendJSONData(JSONA_ERROR,'mess');

    /**
     * HTML-шаблон
     * @var templateView
     */
    public $template = 'default';

    /**
     * Флаг, показывающий, какой тип представляет из себя текущий запрос. По умолчанию - стандартный
     */
    public $LOADTYPE = LT_STANDART;

    private $benchmark;

    public function before() {
        if (Kohana::$profiling === TRUE)
            $this->benchmark = Profiler::start(PR_NORMAL, __METHOD__);

        $this->auth = Auth::instance();
        $this->user = $this->auth->get_user();

        if ($this->template == 'admin') {
            if (!$this->auth->logged_in() || !$this->user->has('roles',ROLE_ADMIN)) {
                $this->template = 'default';
                $this->document_template = 'main';
            }
        }

        View::set_global('_template', $this->template);

		$this->template = 'templates/' . $this->template;

        parent::before(); // Теперь превратим $this->template из текстовой строки в объект View

        $this->location = etc::identifyLocation();
        View::set_global('location', $this->location);

        $this->seo = etc::identifySeo($this->location->country->id);
        View::set_global('_seo', $this->seo);

        // Пользователь всегда будет доступен в любой вьюшке по-умолчанию
        View::set_global('auth', $this->auth);
        View::set_global('user', $this->user);
        View::set_global('controller_link', $this->request->controller());

        // Определяем, что грузит клиент - всю страницу, или маленькое окошко
        if (isset($_REQUEST['showmessage']) && $_REQUEST['showmessage'] == 'true') {
            $this->LOADTYPE = LT_SHOWMESSAGE;
            View::set_global('LOADTYPE',LT_SHOWMESSAGE);
        } elseif(isset($_REQUEST['loadcontent']) && $_REQUEST['loadcontent'] == 'true') {
            $this->LOADTYPE = LT_LOADCONTENT;
            View::set_global('LOADTYPE',LT_LOADCONTENT);
        } else {
            $this->LOADTYPE = LT_STANDART;
            View::set_global('LOADTYPE',LT_STANDART);
        }

        $this->template->content = '';
        $this->template->title = APPLICATION_TITLE;
        $this->template->desc = '';
        $this->template->keywords = '';

        $main_document = Document::instance('main');
        $main_document->addScript('jquery/jquery-1.8.1.min.js');
        $main_document->addScript('jquery/jquery.cookie.js');
        $main_document->addScript('jquery/jquery.easing.js');
        $main_document->addScript('jquery/jquery.json.min.js');
        $main_document->addScript('jquery/jquery.scrollto.js');
        $main_document->addScript('jquery/jquery.tmpl.min.js');
        $main_document->addScript('bootstrap/bootstrap-modal.js');
        $main_document->addScript('bootstrap/bootstrap-tab.js');
        $main_document->addScript('bootstrap/bootstrap-button.js');
        $main_document->addScript('bootstrap/bootstrap-collapse.js');
        $main_document->addScript('bootstrap/bootstrap-dropdown.js');

        $main_document->addScript('core.js');
        $main_document->addScript('class/eventEmitter.js');
        $main_document->addScript('class/message.js');
        $main_document->addScript('class/notify.js');
        $main_document->addScript('class/validation.js');
        $main_document->addScript('class/document.js');
        $main_document->addScript('class/gallery.js');
        $main_document->addScript('class/search.js');
        $main_document->addScript('class/maps.js');
        $main_document->addScript('ckeditor/ckeditor.js');
        $main_document->addScript('api-maps.yandex.ru.js');

        $main_document->addScript('class/navigation.js');
        $main_document->addScript('admin.js');
        $main_document->addScript('swfupload/swfupload.js');
        $main_document->addScript('swfupload/swfupload.queue.js');
        $main_document->addScript('class/upload.js');
        $main_document->joinChosen();
        $main_document->addStyleSheet('css/default/bootstrap.css');
        $main_document->addStyleSheet('css/default/style.css');
        $main_document->addStyleSheet('css/default/cities.css');
        $main_document->addStyleSheet('css/notifies.css');
        $main_document->addStyleSheet('css/table.css');
        $main_document->addStyleSheet('css/message.css');
        $main_document->addStyleSheet('css/photos.css');
        $main_document->addStyleSheet('css/etc.css');
        $main_document->addStyleSheet('css/blog.css');
        $main_document->addStyleSheet('css/search.css');

        $admin_document = Document::instance('admin');
        $admin_document->addScript('jquery/jquery-1.8.1.min.js');
        $admin_document->addScript('jquery/jquery.cookie.js');
        $admin_document->addScript('jquery/jquery.easing.js');
        $admin_document->addScript('jquery/jquery.json.min.js');
        $admin_document->addScript('jquery/jquery.scrollto.js');
		$admin_document->addScript('jquery/jquery.tmpl.min.js');
        $admin_document->addScript('bootstrap/bootstrap-modal.js');
        $admin_document->addScript('bootstrap/bootstrap-tab.js');
        $admin_document->addScript('bootstrap/bootstrap-button.js');
        $admin_document->addScript('bootstrap/bootstrap-collapse.js');
        $admin_document->addScript('bootstrap/bootstrap-dropdown.js');

        $admin_document->addScript('core.js');
        $admin_document->addScript('class/eventEmitter.js');
        $admin_document->addScript('class/message.js');
        $admin_document->addScript('class/notify.js');
        $admin_document->addScript('class/validation.js');
        $admin_document->addScript('class/document.js');
        $admin_document->addScript('class/gallery.js');
        $admin_document->addScript('class/search.js');
        $admin_document->addScript('ckeditor/ckeditor.js');
        $admin_document->addScript('class/ZeroClipboard.js');

        $admin_document->addScript('swfupload/swfupload.js');
        $admin_document->addScript('swfupload/swfupload.queue.js');
        $admin_document->addScript('class/upload.js');

        $admin_document->addScript('class/navigation.js');
        $admin_document->addScript('admin.js');
        $admin_document->joinChosen();
        $admin_document->addStyleSheet('css/admin/bootstrap.css');
        $admin_document->addStyleSheet('css/admin/cities_areas.css');
        $admin_document->addStyleSheet('css/admin/style.css');
        $admin_document->addStyleSheet('css/notifies.css');
        $admin_document->addStyleSheet('css/table.css');
        $admin_document->addStyleSheet('css/message.css');
        $admin_document->addStyleSheet('css/etc.css');
        $admin_document->addStyleSheet('css/photos.css');
        $admin_document->addStyleSheet('css/blog.css');
        $admin_document->addStyleSheet('css/search.css');
    }

    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after() {

        // Если это подзапрос или аяксовый запрос, то отдаем только тело контента, не рендеря весь шаблон
        if (((!$this->request->is_initial() || $this->request->is_ajax()) && ($this->auto_render === true)) && ((!isset($this->needfulltemplate)) || $this->needfulltemplate === false )) {
            $template = View::factory('templates/ajah');
            $template->content = $this->template->content;
            $template->title = $this->template->title;

            $this->response->body($template);
            $this->auto_render = false;
        }

        if ($this->auto_render) {
            // Получение html-кода заголовка
            $this->template->header = Document::compile($this->document_template);
        }

        // Run anything that needs to run after this.
        parent::after();

        if (isset($this->benchmark))
            Profiler::stop($this->benchmark);

    }

    /**
     * Посылает клиенту данные в JSON-формате, которые потом клиентский яваскрипт распарсит и сделает, что надо.
     * @param array $data
     */
    protected function SendJSONData($data) {
        $this->response->headers('Content-Type', 'application/json');

        $json_messages = Array();

        foreach ($data as $key => $mess)
        {
            array_push($json_messages,array(
                'type' => $key,
                'data' => $mess
            ));
        }

        $this->response->body(json_encode($json_messages));
        $this->auto_render = false;
    }

    /**
     * Проверить, залогинен ли пользователь. Если не залогинен - отправить его на страницу авторизации.
     */
    public function checkLogin() {
        if (!$this->auth->logged_in()) {
            $this->request->redirect('/auth');
        }
    }

    /**
     * Проверить, залогинен ли пользователь и является ли он админом.
     */
    public function checkAdminLogin() {
        if (!$this->auth->logged_in() || !$this->user->has('roles',ROLE_ADMIN)) {
            throw new HTTP_Exception_404;
        }
    }

    /**
     * Сообщает в каком формате требуется вернуть данные (HTML или JSON)
     */
    public function isJson() {
        if (isset($_REQUEST['json']) && $_REQUEST['json'] === 'true')
            return TRUE;
        else
            return FALSE;
    }

}

