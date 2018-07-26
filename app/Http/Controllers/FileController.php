<?php
namespace App\Http\Controllers;

use App\Http\Response;
use App\Repositorys\Interfaces\FileInterface;
use App\Repositorys\Interfaces\UploadInterface;
use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers
 *
 * @author  lirui <lirui01@medlinker.com>
 * @date    2018-07-26
 * @desc    文件controller
 */
class FileController extends Controller
{
	private $fileInterface = null;
	
	public function __construct( FileInterface $fileInterface )
	{
		$this->fileInterface = $fileInterface;
	}
	
	/**
	 * 检查文件是否存在
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function isExistFile(Request $request)
	{
		$userId = $request->input('userId', 1);
		$fileMd5 = $request->input('fileMd5');
		$fileSha1 = $request->input('fileSha1');
		$result = $this->fileInterface->check($userId, $fileMd5, $fileSha1);
		return Response::success($result);
	}
	
	/**
	 * 文件上传
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function upload( Request $request )
	{
		$userId = $request->input('userId', 1);
		$files = $request->file('_file_');
		$result = $this->fileInterface->upload($files, app(UploadInterface::class), $userId);
		return Response::success($result);
	}
	
	/**
	 * 文件下载
	 * @param Request $request
	 */
	public function download( Request $request )
	{
		$fileId = $request->input('fileId');
		$this->fileInterface->download($fileId);
	}
}
