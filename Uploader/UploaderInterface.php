<?php
namespace FW\Uploader;

interface UploaderInterface
{
	public function __construct($file);
	public function save():bool;
}