<?php
namespace FW\Uploader;

interface UploaderInterface
{
	public function __construct($file,$options);

	/**
	 * @param mixed $to
	 * @param array $options
	 * @return $this
	 */
	public function save($to = false,$options = []);
}