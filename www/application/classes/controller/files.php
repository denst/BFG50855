<?

class Controller_Files extends Template {

    function action_uploadimg() {
        $this->auto_render = false;

        $array = Validation::factory($_FILES);
        $array->rule('file', 'Upload::not_empty')
                ->rule('file', 'Upload::type', array(':value', array('jpg','jpeg', 'png')));

        if ($array->check())
        {

            $ext = mb_strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            $file = ORM::factory('file');
            $file->name = date('Y-m-d-i-') . (string)rand(10000,9999999999) . '.' . $ext;
            $file->users_id = Arr::get($_REQUEST,'user_id');
            $file->save();
            $file->mkdir();

            Upload::save($_FILES['file'], $file->name, $file->folder(false), 0777);

            self::process($file);

            // отображаем файл
            if (Arr::get($_REQUEST,'flashupload') == 'true')
            {
                $this->response->body(json_encode(array(
                    'id' => $file->id,
                    'name' => $file->name,
                    'path' => $file->path(),
                    'thumb' => $file->thumb()
                )));
            } else {
                $this->response->body('<img src="'.$file->path().'" />');
            }

        }

    }

    // Сохранить изображение
    static function process($file) {

        if (!file_exists(DOCROOT . $file->path()))
            return null;

        //$ext = mb_strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $image = Image::factory(DOCROOT . $file->path(),'gd'); // Imagick

        // Если фотография шире 1024 пикселей - уменьшаем ее и присобачиваем ей водяной знак
        if ($image->width > 1024)
        {
            $image
                ->resize(1024,768, Image::WIDTH)
                //->watermark(Image::factory(WATERMARK,'Imagick'), TRUE, TRUE, 100)
                ->save(null,85);
        }

        // Если у файла отсутствует превьюшка - сделаем ее
        $thumb = $file->thumb();
        if (!file_exists(DOCROOT . $thumb))
        {
            $image->resize(180, 180, Image::WIDTH)->save(DOCROOT . $thumb, 90);
        }

    }
}

?>
