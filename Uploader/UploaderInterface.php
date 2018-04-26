<?php
namespace FW\Uploader;

interface UploaderInterface
{
	public function __construct($file,$options);
	public function save($to,$options = []):bool;
}