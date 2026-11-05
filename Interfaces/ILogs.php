<?php

interface ILogs
{
	public static function ShowMsgError($query, $title, $user, $errorException, $database);
	public static function WriteFile($path, $fileName, $content, $date="Y-m-d H:i:s", $extension="txt");
}