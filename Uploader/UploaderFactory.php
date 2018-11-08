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
			'avi' => ['video/avi','video/x-msvideo','application/x-troff-msvideo', 'video/msvideo', 'video/xmpg2'],
			'mpeg' => ['video/mpeg'],
			'ogv' => ['video/ogg'],
			'webm' => ['video/webm'],
			'flv' => ['video/x-flv'],
			'm4v' => ['video/mp4', 'video/mpeg4', 'video/x-m4v'],
			'm1v' => ['video/mpeg'],
			'm2v' => ['video/mpeg'],
			'mp2' => ['video/mpeg','video/x-mpeg', 'video/x-mpeq2a'],
			'mpe' => ['video/mpeg'],
			'mpg' => ['video/mpeg'],
			'mov' => ['video/quicktime'],
			'mqv' => ['video/quicktime'],
			'rv' => ['video/vnd.rn-realvideo','video/x-pn-realvideo'],

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
			'm4a' => ['audio/mp4','audio/x-m4a'],
			'm4b' => ['audio/mp4'],
			'flac' => ['audio/flac'],
			'webm' => ['audio/webm'],
			'wma' => ['audio/x-ms-wma','video/x-ms-asf'],
			'mid' => ['audio/x-midi','application/x-midi', 'audio/mid', 'audio/midi', 'audio/soundtrack'],
			'midi' => ['audio/x-midi','application/x-midi', 'audio/mid', 'audio/midi', 'audio/soundtrack'],
			'rpm' => ['audio/vnd.rn-realaudio','audio/vnd.pn-realaudio', 'audio/x-pn-realaudio', 'audio/x-pn-realaudio-plugin', 'audio/x-pn-realvideo', 'audio/x-realaudio']
		],
		'doc' => [
			'doc' => ['application/msword','application/vnd.ms-word','application/doc', 'application/msword-doc', 'application/vnd.msword', 'application/winword', 'application/word', 'application/x-msw6', 'application/x-msword', 'application/x-msword-doc'],
			'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-word.document.12', 'application/vnd.openxmlformats-officedocument.word'],
			'xls' => ['application/vnd.ms-excel','application/msexcel','application/x-msexcel','application/x-ms-excel','application/x-excel','application/x-dos_ms_excel','application/xls','application/x-xls'],
			'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
			'abw' => ['application/abiword'],
			'ppt' => ['application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation'],
			'odt' => ['application/vnd.oasis.opendocument.text'],
			'ott' => ['application/vnd.oasis.opendocument.text-template'],
			'odg' => ['application/vnd.oasis.opendocument.graphics'],
			'otg' => ['application/vnd.oasis.opendocument.graphics-template'],
			'odp' => ['application/vnd.oasis.opendocument.presentation'],
			'otp' => ['application/vnd.oasis.opendocument.presentation-template'],
			'ods' => ['application/vnd.oasis.opendocument.spreadsheet'],
			'ots' => ['application/vnd.oasis.opendocument.spreadsheet-template'],
			'odc' => ['application/vnd.oasis.opendocument.chart'],
			'otc' => ['application/vnd.oasis.opendocument.chart-template'],
			'odi' => ['application/vnd.oasis.opendocument.image'],
			'oti' => ['application/vnd.oasis.opendocument.image-template'],
			'odf' => ['application/vnd.oasis.opendocument.formula'],
			'otf' => ['application/vnd.oasis.opendocument.formula-template'],
			'odm' => ['application/vnd.oasis.opendocument.text-master'],
			'oth' => ['application/vnd.oasis.opendocument.text-web'],
			'rtf' => ['application/rtf','application/richtext', 'application/x-rtf', 'text/richtext', 'text/rtf'],
			'rtx' => ['application/rtf','application/richtext', 'application/x-rtf', 'text/richtext', 'text/rtf'],
			'rar' => ['application/rar','application/x-rar-compressed', 'application/octet-stream', 'application/x-rar'],
			'zip' => ['application/zip', 'application/octet-stream'],
			'7z' => ['application/x-7z-compressed'],
			'gzip' => ['application/gzip'],
			'bz2' => ['application/x-bzip2','application/bzip2', 'application/x-bz2', 'application/x-bzip'],
			'tar' => ['application/tar','application/x-tar'],
			'tgz' => ['application/gzip','application/gzip-compressed', 'application/gzipped', 'application/x-gunzip', 'application/x-gzip'],
			'gz' => ['application/gzip','application/gzip-compressed', 'application/gzipped', 'application/x-gunzip', 'application/x-gzip'],
			'gtar' => ['application/tar','application/x-gtar', 'application/x-tar'],
			'z' => ['application/x-compress','application/z', 'application/x-z'],
			'ttf' => ['application/x-font-ttf'],
			'ai' => ['application/illustrator'],
			'indd' => ['application/x-indesign'],
			'psd' => ['application/photoshop','application/psd', 'application/x-photoshop', 'image/photoshop', 'image/psd', 'image/x-photoshop', 'image/x-psd', 'image/vnd.adobe.photoshop'],
			'pdf' => ['application/pdf','application/acrobat', 'application/nappdf', 'application/x-pdf', 'application/vnd.pdf', 'text/pdf', 'text/x-pdf'],
			'css' => ['text/css','application/css-stylesheet'],
			'ico' => ['image/x-ico'],
			'svg' => ['image/svg+xml','application/svg+xml'],
		],
	];


	public $sizes = [
		'image' => '30000000',
		'video' => '512000000',
		'audio' => '40000000',
		'doc' => '512000000',
		'application' => '100000000'
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
					$file['real_type'] = $k;

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