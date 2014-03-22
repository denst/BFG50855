<?

class Model_File extends ORM {

	protected $_table_name = 'files';

    protected $_has_many = array(
		'adverts' => array(
            'model'         => 'file',
            'through'       => 'adverts_has_files',
            'foreign_key'   => 'files_id',
            'far_key'       => 'adverts_id',
        ),
    );

	protected $_belongs_to = array(
		'user' => array(
            'model' => 'user',
            'foreign_key' => 'users_id',
        ),
    );

    public function folder($slash = true) {
        $slash = $slash ? '/' : '';
        return $slash . 'upload/' . $this->users_id . $slash;
    }

    public function path() {
        return $this->folder() . $this->name;
    }

    public function mkdir() {
        $folder = $this->folder(false);
        if (!file_exists(DOCROOT . '/' . $folder))
            mkdir(DOCROOT . $folder);
    }

    public function thumb() {
		if ($this->loaded())
			return $this->folder() . pathinfo($this->name, PATHINFO_FILENAME) . '-thumb.' . pathinfo($this->name, PATHINFO_EXTENSION);
		else
			return '/upload/nophoto.png';
    }

    // Удаляет файл вместе с изображением и превьюшкой
    public function delete() {

        if (file_exists(DOCROOT . $this->path()))
            unlink(DOCROOT . $this->path());

        if (file_exists(DOCROOT . $this->thumb()))
            unlink(DOCROOT . $this->thumb());

        parent::delete();

    }

}

?>
