<?php

interface IActionFiles
{
    public static function CreateFile($fileName, $data, $nameArray);
    public static function CreateFileFunctions($fileName, $data, $fileType);
    public static function Thumbnail($imagePath, $thumbnailPath, $newHeight, $mode);
    public static function LoadFileBDServer($parentDirectory, $fileName, $tmpFile, $typeFile, $sizeFile, $unitMeasurement, $errorFile, $mode, $newFileName);
    public static function LoadPhoto();
    public static function DownloadFiles($Id);
    public static function ViewPDF($Id);
}