<?php
namespace FW\Uploader;

class UploaderFactory
{
	use UploaderError;

	/**
	 * Полный список допустимых расширений и mime_type для класса
	 * @var array
	 * @example если хотим ограничить типы, укажите $upload->setTypes('image') или $upload->setTypes('image,video,audio');
	 */
	private static $types = [
		'image' => [
			'jpg' => ['image/jpeg','image/pjpeg'],
			'jpeg' => ['image/jpeg','image/pjpeg'],
			'gif' => ['image/gif'],
			'png' => ['image/png'],
			'tif' => ['image/tiff'],
			'tiff' => ['image/tiff'],
			'webp' => ['image/webp']
		],
		'video' => [
			'mp4' => ['video/mp4'],
			'avi' => ['video/x-msvideo'],
			'mpeg' => ['video/mpeg'],
			'ogv' => ['video/ogg'],
			'webm' => ['video/webm'],
			'flv' => ['video/x-flv']

		],
		'audio' => [
			'mp3' => ['audio/mp4'],
			'acc' => ['audio/aac'],
			'wav' => ['audio/wav','audio/x-wav'],
			'oga' => ['audio/ogg'],
			'weba' => ['audio/webm']
		],
		'doc' => [
			'doc' => ['application/msword'],
			'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
			'rtf' => ['application/rtf']
		],
	];

	private static $sizes = [
		'image' => '20000000',
		'video' => '256000000',
		'audio' => '20000000',
		'doc' => '10000000'
	];

	static function setTypes($types)
	{
		foreach(self::$types as $k=>$v) {
			if(!in_array($k,$types)) {
				unset(self::$types[$k]);
			}
		}
	}

	static function setSizes($sizes)
	{
		// Доделать!
	}


	// NAME, TYPE, SIZE, TMP_NAME, ERROR
	static function init($file, $isUpload = true, $types = [], $sizes = [])
	{
		if(count($types)) self::setTypes($types);
		if(count($sizes)) self::setSizes($sizes);

		if($file['error'] === UPLOAD_ERR_NO_FILE) {
			(new \FW\Log\Log)->error('Uploader: incorrect script using');
			self::setError('Incorrect script using');
		}

		if(!file_exists($file['tmp_name'])) {
			self::setError('File is not uploaded');
		}

		switch ($file['error']) {
			case UPLOAD_ERR_INI_SIZE: case UPLOAD_ERR_FORM_SIZE:
				return self::setError('File size is too large');
				break;
			case UPLOAD_ERR_PARTIAL:
				return self::setError('File is not chosen or not uploaded');
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				(new \FW\Log\Log)->alert('Uploader: missing a temporary folder');
				return self::setError('Server error: Missing a temporary folder');
				break;
			case UPLOAD_ERR_CANT_WRITE:
				(new \FW\Log\Log)->alert('Uploader: cant write to disk');
				return self::setError('Server error: Failed to write file to disk');
				break;
			case UPLOAD_ERR_EXTENSION:
				(new \FW\Log\Log)->alert('Uploader: server extension error');
				return self::setError('Server extension error');
				break;
			default:
				return self::setError('Unknown server error');
				break;
		}

		$file['real_ext'] = pathinfo($file['tmp_name'], PATHINFO_EXTENSION);
		$file['real_mime_type'] = mime_content_type($file['tmp_name']);
		$tmp = $explode('/', $file['real_mime_type']);
		$file['real_type'] = $tmp[0];

		if(!isset($sizes[$file['real_type']]) || filesize($file['tmp_name']) > $sizes[$file['real_type']]) {
			return self::setError('File size is too large');
		}

		foreach(self::$types as $k=>$v) {
			foreach($v as $k2=>$v2) {
				if($k2 === $file['real_ext']) {
					if(!in_array($file['real_mime_type'], $v2)) {
						return self::setError('Incorrect file type');
					} else {

						if($isUpload) {
							if(!move_uploaded_file($file['tmp_name'], \Core::$ROOT.'/uploads/tmp/'.$file['tmp_name'])) {
								return self::setError('Imposible to upload file');
							}
							$file['tmp_name'] = \Core::$ROOT.'/uploads/tmp/'.$file['tmp_name'];
						}

						$class = 'Uploader'.ucfirst($file['real_type']);
						return new $class($file);
					}
				}
			}
		}

		return self::setError('Incorrect extension');
	}
}