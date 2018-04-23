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
	public $types = [
		'image' => [
			'jpg' => ['image/jpeg','image/pjpeg'],
			'jpeg' => ['image/jpeg','image/pjpeg'],
			'png' => ['image/png'],
			'gif' => ['image/gif'],
			'bmp' => ['image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'],
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
			'mp3' => ['audio/mp4','audio/mpeg'],
			'mpeg' => ['audio/mpeg'],
			'acc' => ['audio/aac'],
			'wav' => ['audio/wav','audio/x-wav'],
			'oga' => ['audio/ogg', 'audio/vorbis'],
			'ogg' => ['audio/ogg', 'audio/vorbis'],
			'opus' => ['audio/ogg'],
			'weba' => ['audio/webm'],
			'm3u8' => ['audio/mpegurl'],
			'm4a' => ['audio/mp4'],
			'm4b' => ['audio/mp4'],
			'flac' => ['audio/flac'],
			'webm' => ['audio/webm'],
			'wma' => ['audio/x-ms-wma'],
		],
		'doc' => [
			'doc' => ['application/msword'],
			'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
			'rtf' => ['application/rtf']
		],
	];


	private $sizes = [
		'image' => '20000000',
		'video' => '256000000',
		'audio' => '20000000',
		'doc' => '10000000'
	];

	public function setTypes($types)
	{
		foreach($this->types as $k=>$v) {
			if(!in_array($k,$types)) {
				unset($this->types[$k]);
			}
		}
	}

	public function setSizes($sizes)
	{
		// Доделать!
	}

	private function generateRandomString():string {
		$tmp = [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
		$name = time();
		$max = count($tmp)-1;
		for($i=0;$i<6;++$i) {
			$name .= $tmp[rand(0, $max)];
		}
		return $name;
	}


	// NAME, TYPE, SIZE, TMP_NAME, ERROR

	/**
	 * @param $file
	 * @param bool $isUpload
	 * @param array $types
	 * @param array $options
	 * @return UploaderInterface|UploaderLibrary
	 * @throws \Exception
	 */
	public function init($file, $isUpload = true, $types = [], $options = [])
	{
		if(count($types)) $this->setTypes($types);
		if(isset($options['sizes'])) $this->setSizes($options['sizes']);

		if(!$isUpload) {
			$file = [
				'tmp_name' => $file,
				'name' => $file
			];
		}

		if(!file_exists($file['tmp_name'])) {
			$this->setError('File is not uploaded');
		}

		if(!empty($file['error']) && $isUpload) {
			switch($file['error']) {
				case UPLOAD_ERR_NO_FILE:
					(new \FW\Log\Log)->error('Uploader: incorrect script using');
					$this->setError('Incorrect script using');
					break;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					return $this->setError('File size is too large');
					break;
				case UPLOAD_ERR_PARTIAL:
					return $this->setError('File is not chosen or not uploaded');
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					(new \FW\Log\Log)->alert('Uploader: missing a temporary folder');

					return $this->setError('Server error: Missing a temporary folder');
					break;
				case UPLOAD_ERR_CANT_WRITE:
					(new \FW\Log\Log)->alert('Uploader: cant write to disk');

					return $this->setError('Server error: Failed to write file to disk');
					break;
				case UPLOAD_ERR_EXTENSION:
					(new \FW\Log\Log)->alert('Uploader: server extension error');

					return $this->setError('Server extension error');
					break;
				default:
					return $this->setError('Unknown server error');
					break;
			}
		}
		$file['real_ext'] = mb_strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$file['real_mime_type'] = mb_strtolower(mime_content_type($file['tmp_name']));
		$tmp = explode('/', $file['real_mime_type']);
		$file['real_type'] = $tmp[0];

		if(!isset($this->sizes[$file['real_type']]) || filesize($file['tmp_name']) > $this->sizes[$file['real_type']]) {
			return $this->setError('File size is too large');
		}
		foreach($this->types as $k=>$v) {
			foreach($v as $k2=>$v2) {
				if(in_array($file['real_mime_type'], $v2)) {
					if(!isset($v[$file['real_ext']])) {
						return $this->setError('Incorrect extension: '.$file['real_ext'].' or mime-type: '.$file['real_mime_type']);
					}

					$file['real_ext'] = $k2;

					$file['file_name'] = $this->generateRandomString().'.'.$file['real_ext'];
					if($isUpload) {
						$file['tmp_destination'] = \Core::$ROOT.'/uploads/tmp/'.$file['file_name'];
						if(!move_uploaded_file($file['tmp_name'], $file['tmp_destination'])) {
							return $this->setError('Imposible to upload file');
						}
						$file['tmp_name'] = \Core::$ROOT.'/uploads/tmp/'.$file['tmp_name'];
					} else {
						$file['tmp_destination'] = $file['tmp_name'];
					}

					$class = '\FW\Uploader\Uploader'.ucfirst($file['real_type']);
					return new $class($file, $options);
				}
			}
		}

		return $this->setError('Incorrect extension: '.$file['real_ext'].' or mime-type: '.$file['real_mime_type']);
	}
}