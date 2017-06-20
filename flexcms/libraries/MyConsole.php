<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MyConsole
{
	public function __construct()
	{
		require_once APPPATH.'third_party/codeigniter-forensics/libraries/Console.php';
	}
}