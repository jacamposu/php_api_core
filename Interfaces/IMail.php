<?php

interface IMail
{
	public static function Send ($mailTo, $subject, $message, $mailFrom, $typeContent='text/plain', $attachment=[], $hiddenCopy='', $copy='');
	public static function WsRest($protocolWS, $urlSendgrid, $params, $headers, $timeout, $Cookie);
}