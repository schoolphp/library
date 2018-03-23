<?php
namespace FW\Form;

class Uploader
{
	private $basefilename = '';
	private $img;
	private $prop;
	private $uploaded = false;
	private $source;
	
	public $subdir = '';
	public $tmpfilename = '';
	public $filename = '';
	public $error = '';
	public $notuploaded = false;
	
	public $minwidth = 200;
	public $minheight = 150;

	public $watermark = '/uploads/watermark.png';

	public $save_origin = true;

	private function setError($error) {
		throw new \Exception(hc($this->basefilename).'. '.$error);
	}

	public function generateFileName($file,$name = '') {
		if(empty($name)) {
			$tmp = date('U').strtolower(substr($file, -8, 4)).'.jpg';
		} else {
			$tmp = $name;
		}
		$this->tmpfilename = \Core::$ROOT.'/uploads/full/'.$tmp;
		$this->filename = $this->subdir.$tmp;
		return true;
	}

	public function uploadFile() {

	}

	public function upload($file, $name = '') {
		$this->uploaded = true;
		if(!empty($file['name'])) {
			$this->basefilename = $file['name'];
		}

		if($file['error'] != UPLOAD_ERR_OK) {
			switch($file['error']) {
				case UPLOAD_ERR_INI_SIZE: case UPLOAD_ERR_FORM_SIZE:
					$this->setError('File size is too large'); break;
				case UPLOAD_ERR_PARTIAL:
					$this->setError('File is not chosen or not uploaded'); break;
				case UPLOAD_ERR_NO_FILE:
					$this->setError('File is not chosen or not uploaded'); break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$this->setError('Server error: Missing a temporary folder'); break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->setError('Server error: Failed to write file to disk'); break;
				case UPLOAD_ERR_EXTENSION:
					$this->setError('Server extension error'); break;
				default:
					$this->setError('Unknown server error'); break;
			}
		}

		if(!file_exists($file['tmp_name'])) {
			$this->setError('File is not uploaded');
		}

		$filesize = filesize($file['tmp_name']);
		if($file['size'] < 1000 || $filesize < 1000) {
			$this->setError('File size is too small');
		} elseif($file['size'] > 10000000 || $filesize > 10000000) {
			$this->setError('File size is too large');
		}

		$this->img = getimagesize($file['tmp_name']);

		if(!in_array($this->img['mime'],['image/jpeg','image/png']) || !in_array($file['type'],['image/jpeg','image/png'])) {
			$this->setError('Incorrect file type');
		} elseif($this->img[0] < $this->minwidth) {
			$this->setError('Width of file is too small. Minimum require '.$this->minwidth.' px');
		} elseif($this->img[1] < $this->minheight) {
			$this->setError('Height of file is too small. Minimum require '.$this->minheight.' px');
		}

		$this->prop = $this->img[0]/$this->img[1];
		if(($this->prop >= 1 && $this->prop > 5) || ($this->prop <= 1 && $this->prop < 0.2)) {
			$this->setError('Incorrect file proportion');
		}

		$this->generateFileName($file['tmp_name'], $name);
		if(!$this->notuploaded) {
			if(!move_uploaded_file($file['tmp_name'],$this->tmpfilename)) {
				$this->setError('File uploading fault');
			}
		} else {
			if(!copy($file['tmp_name'],$this->tmpfilename)) {
				$this->setError('File uploading fault');
			}
		}

		ini_set('gd.jpeg_ignore_warning', 1);

		if($this->img['mime'] == 'image/png' || $file['type'] == 'image/png') {
			$this->source = imagecreatefrompng($this->tmpfilename);
		} else {
			$this->source = imagecreatefromjpeg($this->tmpfilename);
			if(function_exists('exif_read_data')) {
				$exif = exif_read_data($this->tmpfilename);
				if(!empty($exif['Orientation'])) {
					switch($exif['Orientation']) {
						case 8:
							$this->source = imagerotate($this->source,90,0);
							$tmp = $this->img[0];
							$this->img[0] = $this->img[1];
							$this->img[1] = $tmp;
							$this->prop = $this->img[0]/$this->img[1];
							break;
						case 3:
							$this->source = imagerotate($this->source,180,0);
							break;
						case 6:
							$this->source = imagerotate($this->source,-90,0);
							$tmp = $this->img[0];
							$this->img[0] = $this->img[1];
							$this->img[1] = $tmp;
							$this->prop = $this->img[0]/$this->img[1];
							break;
					}
				}
			}
		}

		return true;
	}
	
	public function resize($width,$height,$to,$watermark = false) {
		$beginwidth = $width;
		if(empty($this->filename) || empty($this->prop) || !is_array($this->img) || !$this->source) {
			$this->setError('File is not included to resize');
		}
		
		$tmp = $width/$this->prop;
		if($tmp > $height) {
			$width = round($height*$this->prop);
		} else {
			$height = round($tmp);
		}

		$thumb = imagecreatetruecolor($width, $height);
		imagecopyresampled($thumb, $this->source, 0, 0, 0, 0, $width, $height, $this->img[0], $this->img[1]);
			
		if($watermark) {
			$stamp = imagecreatefrompng(\Core::$ROOT.$this->watermark);
			$watermarksize = getimagesize(\Core::$ROOT.$this->watermark);
			$watermarkprop = 600/$beginwidth;
			if($watermarkprop != 1) {
				$targetImage = imagecreatetruecolor( $watermarksize[0]/$watermarkprop, $watermarksize[1]/$watermarkprop );   
				imagealphablending( $targetImage, false );
				imagesavealpha( $targetImage, true );
				
				imagecopyresampled( $targetImage, $stamp, 
									0, 0, 
									0, 0, 
									$watermarksize[0]/$watermarkprop, $watermarksize[1]/$watermarkprop, 
									$watermarksize[0], $watermarksize[1]);
				$stamp = $targetImage;
			}
			$marge_right = 5;
			$marge_bottom = 5;
			$sx = imagesx($stamp);
			$sy = imagesy($stamp);
			imagecopy($thumb, $stamp, $width - $sx - $marge_right, $height - $sy - $marge_bottom, 0, 0, $sx, $sy);
		}
		
		imagejpeg($thumb,\Core::$ROOT.'/uploads/'.$to.'/'.$this->filename,100);
		imagedestroy($thumb);
		return true;
	}
	
	public function __destruct() {
		if(!$this->save_origin && $this->uploaded && file_exists($this->tmpfilename)) {
			unlink($this->tmpfilename);
		}
	}
}
