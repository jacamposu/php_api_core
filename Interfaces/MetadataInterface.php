<?php 

interface IMetadata
{
	public function InfoPage(string $numPage, string $startFrom);
	public function StrQuery(array $infoPage);
	public function DdlSaved();
	public function DdlList();
	public function Options(array $infoPage);
	public function AddScripts();
	public function AddScriptsFooter();
	public function AddLinks();
	public function HiddenFields();
}