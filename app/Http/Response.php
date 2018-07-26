<?php
namespace App\Http;
use Illuminate\Support\Facades\App;

/**
 * @desc   PhpStorm.
 * @author thooh
 * @date   2018/7/20
 */
class Response
{
	public static function success( $data = [] )
	{
		return response()->json([
			'status'  => true,
			'code'    => 200,
			'message' => trans('message.code.' . 200 ),
			'data'    => $data,
		]);
	}
	
	public static function fail( $code, $message, $data = [] )
	{
		return response()->json([
			'status'  => false,
			'code'    => $code,
			'message' => trans('message.code.' . $code) ?: $message,
			'data'    => $data,
		]);
	}
}