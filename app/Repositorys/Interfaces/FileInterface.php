<?php
namespace App\Repositorys\Interfaces;
/**
 * 文件Interface
 * Interface FileInterface
 * @package App\Repositorys\Interfaces
 */
interface FileInterface
{

	// 检查文件
	public function check($userId, $fileMd5, $fileSha1 = null);
	
	// 上传文件
	public function upload($files, UploadInterface $upload, $userId);
	
	// 下载文件
	public function download($fileId);
	
}