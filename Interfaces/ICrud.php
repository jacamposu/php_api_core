<?php 

interface ICrud
{
	public function GetDataTable(object $result, object $totalRecords, int $start);
	
	public function GetDataForm(string $type, object $form, object $list, object $listSaved);
	// public function getNewLinksScripts();
	// public function table($result, $totalRecords, $action);
	// public function form($action, $result, $list);
	// public function oneRecord($result);
	// // public function showBrowserView($action);
	// public function add();
	// public function edit($action, $strQuery);
	// public function deleteRecord($dbAlternative);
	// public function LoadMassiveContent();
	// public function downloads($dbAlternative);
}