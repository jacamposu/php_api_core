<?php 

interface IFiles
{
	public function downloadFile($id);
	public function viewFile($recordEdit, $FilType);
	public function LoadFile();
	public function Versions();
	public function deleteDirectory();
	public function deleteFile();
}