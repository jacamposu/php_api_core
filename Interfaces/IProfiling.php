<?php

interface IProfiling
{
	public function FormEditProfileMenu($action, $hideMenu);
	public function showFormEditProfile($action, $hideMenu);
	public function profileGenerate();
	public function editProfile();
	public function editProfileMenu();
	public static function GenerateFile($descUserType);
	public static function Populate ($userType);
}