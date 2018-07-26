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
		200 => '成功',
		500 => '服务器异常',
		
		// file
		503001 => '文件上传失败',
		503002 => '数据库不存在文件记录',
		503003 => '参数中不存在文件',
		503004 => '文件打开失败',
		503005 => '文件读取失败',
		503006 => '文件关闭失败',
		503007 => '添加文件记录失败',
		503008 => '添加用户文件记录失败',
		503009 => '更新用户文件记录失败',
		503010 => '服务器不存在文件',
		503011 => '文件下载失败',
	],

];