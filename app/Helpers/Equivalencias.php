<?php
namespace App\Helpers;

class Equivalencias
{

	private static $urlProduccion = 'http://127.0.0.1:8000/';

	public static function urlProduccion()
	{
		return self::$urlProduccion;
	}

}
