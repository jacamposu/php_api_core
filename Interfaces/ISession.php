<?php

interface ISession
{
	public static function Start($urlRedirect='');
	public static function Close($closeFromFrame = false);
}
