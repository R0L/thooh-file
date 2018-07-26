<?php
namespace App\Repositorys\Implement\File;

use App\Repositorys\Interfaces\UploadInterface;

/**
 * @desc   本地上传service
 * @author lirui <lirui01@medlinker.com>
 * @date   2018-7-26
 */
class LocalUploadService implements UploadInterface
{
	/**
	 * 上传文件
	 * @param $file
	 * @return string
	 * @throws \Exception
	 */
	public function upload( $file )
	{
		$fileName = pathinfo($file, PATHINFO_FILENAME);
		$destination = storage_path('app/upload/') . $fileName;
		try {
			move_uploaded_file($file, $destination);
		}
		catch (\Exception $e) {
			throw new \Exception('upload file fail : ' . $e->getMessage(),
				503001);
		}
		return 'app/upload/' . $fileName;
	}
}