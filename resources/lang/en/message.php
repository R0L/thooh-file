<?php
/**
 * @desc   PhpStorm.
 * @author lirui <lirui01@medlinker.com>
 * @date   2018-7-26
 */

return [
	
	/*
	|--------------------------------------------------------------------------
	| customized http code
	|--------------------------------------------------------------------------
	|
	| The first number is error type, the second and third number is
	| product type, and it is a specific error code from fourth to
	| sixth.But the success is different.
	|
	*/
	
	'code' => [
		200 => 'success',
		500 => 'server get away',
		
		// file
		503001 => 'upload file fail',
		503002 => 'database not exists file record',
		503003 => 'param not exists file',
		503004 => 'file open err',
		503005 => 'file read err',
		503006 => 'file close err',
		503007 => 'add file record fail',
		503008 => 'add user file record fail',
		503009 => 'update user file record fail',
		503010 => 'server file not exists',
		503011 => 'file download fail',
	],

];