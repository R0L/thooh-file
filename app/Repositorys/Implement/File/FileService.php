<?php
/**
 * @desc   文件服务service
 * @author lirui <lirui01@medlinker.com>
 * @date   2018-7-25
 */
namespace App\Repositorys\Implement\File;

use App\Exceptions\GeneralException;
use App\Exceptions\NotExistsException;
use App\Models\File as FileModel;
use App\Models\UserFile;
use App\Repositorys\Interfaces\FileInterface;
use App\Repositorys\Interfaces\UploadInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FileService implements FileInterface
{
	/**
	 * 检查服务是否存在文件
	 * @param $userId
	 * @param $fileMd5
	 * @param null $fileSha1
	 * @return mixed
	 * @throws GeneralException
	 * @throws NotExistsException
	 */
	public function check( $userId, $fileMd5, $fileSha1 = null )
	{
		$query = FileModel::where('md5', $fileMd5)->select([
			'id',
			'savename',
			'savepath'
		]);
		if (isset($fileSha1)) {
			$query->where('sha1', $fileSha1);
		}
		$result = $query->first();
		if (empty($result)) {
			throw new NotExistsException('database not exists file record', 503002);
		}
		$this->saveUserFile($userId, $result['id']);
		$ret['fileId'] = $result['id'];
		$ret['fileUrl'] = $result['savepath'] . DIRECTORY_SEPARATOR . $result['savename'];
		return $ret;
	}
	
	/**
	 * 上传文件
	 * @param array $files
	 * @param UploadInterface $upload
	 * @param $userId
	 * @return array
	 * @throws \Exception
	 */
	public function upload( $files = array(), UploadInterface $upload, $userId )
	{
		if (!is_array($files)) {
			$files = array( $files );
		}
		$ret = array();
		foreach ( $files as $file ) {
			$ret[] = $this->uploadOne($file, $upload, $userId);
		}
		return $ret;
	}
	
	/**
	 * 上传单个文件
	 * @param UploadedFile $file
	 * @param UploadInterface $uploadInterface
	 * @param $userId
	 * @return mixed
	 * @throws \Exception
	 */
	private function uploadOne( UploadedFile $file, UploadInterface $uploadInterface, $userId )
	{
		if (empty($file)) {
			throw new GeneralException('param not exists file', 503003);
		}
		$fileTempName = $file->getRealPath();
		$handle = fopen($fileTempName, "r");
		if (false === $handle) {
			throw new GeneralException('file open err : ' . $fileTempName, 503004);
		}
		$content = fread($handle, filesize($fileTempName));//读为二进制
		if (false === $content) {
			throw new GeneralException('file read err : ' . $fileTempName, 503005);
		}
		if (false === fclose($handle)) {
			throw new GeneralException('file close err : ' . $fileTempName, 503005);
		}
		$fileMd5 = md5($content);
		$fileSha1 = sha1($content);
		$ret = array(
			'fileId'  => '',
			'fileUrl' => ''
		);
		try {
			$ret = $this->check($userId, $fileMd5, $fileSha1);
		}
		catch (NotExistsException $e) {
			DB::beginTransaction();
			try {
				$fileUrl = $uploadInterface->upload($fileTempName);
				$filesResult = $this->saveFileRecord($file, $fileMd5, $fileSha1, $fileUrl);
				$this->saveUserFile($userId, $filesResult['id']);
				$ret['fileId'] = $filesResult['id'];
				$ret['fileUrl'] = $fileUrl;
			}
			catch (\Exception $e) {
				DB::rollback();
				throw new GeneralException($e->getMessage(), $e->getCode());
			}
			DB::commit();
		}
		catch (\Exception $e) {
			throw new GeneralException($e->getMessage(), $e->getCode());
		}
		return $ret;
	}
	
	/**
	 * 保存文件上传记录
	 * @param UploadedFile $file
	 * @param $fileMd5
	 * @param $fileSha1
	 * @param $fileUrl
	 * @return FileModel|\Illuminate\Database\Eloquent\Model
	 * @throws GeneralException
	 */
	private function saveFileRecord( UploadedFile $file, $fileMd5, $fileSha1, $fileUrl )
	{
		$fileMineType = $this->getMimeType($file);
		$fileExt = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
		$savepath = pathinfo($fileUrl, PATHINFO_DIRNAME);
		$savename = pathinfo($fileUrl, PATHINFO_FILENAME);
		$create_data = [
			'name'        => $file->getClientOriginalName(),
			'savename'    => $savename,
			'savepath'    => $savepath,
			'ext'         => $fileExt,
			'mime'        => $fileMineType,
			'size'        => $file->getSize(),
			'md5'         => $fileMd5,
			'sha1'        => $fileSha1,
			'location'    => 0,
			'create_time' => time(),
		];
		$result = FileModel::create($create_data);
		if (false === $result) {
			throw new GeneralException('add file record fail : ' . var_export($create_data, true), 503007);
		}
		return $result;
	}
	
	/**
	 * 保存用户文件记录
	 * @param $userId
	 * @param $fileId
	 * @return UserFile|bool|\Illuminate\Database\Eloquent\Model|null
	 * @throws GeneralException
	 */
	private function saveUserFile( $userId, $fileId )
	{
		$times = 1;
		$result = UserFile::where([
			'user_id' => $userId,
			'file_id' => $fileId
		])->first();
		if (null == $result) {
			$create_data = [
				'user_id' => $userId,
				'file_id' => $fileId,
				'times'   => $times,
			];
			$result = UserFile::create($create_data);
			if (false === $result) {
				throw new GeneralException('add user file record fail : ' . var_export($create_data, true), 503008);
			}
		}
		else {
			$times += $result['times'];
			$update_data = [ 'times' => $times ];
			$where_data = [
				'user_id' => $userId,
				'file_id' => $fileId
			];
			$result = UserFile::where($where_data)->update($update_data);
			if (false === $result) {
				throw new GeneralException('update user file record fail : ' . var_export(array_merge($update_data, $where_data), true), 503009);
			}
		}
		return $result;
	}
	
	/**
	 * 获取文件mimeType
	 * @param UploadedFile $uploadedFile
	 * @return null|string
	 */
	private function getMimeType( UploadedFile $uploadedFile )
	{
		//		$fileTempName = $uploadedFile->getRealPath() ?: $uploadedFile->getPathname();
		//		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		//		$result = $finfo->file($fileTempName);
		//		if (false === $result)
		//		{
		//			throw new \Exception('get file mime type fail');
		//		}
		//		return $result;
		return $uploadedFile->getClientMimeType();
	}
	
	/**
	 * 文件下载
	 * @param $fileId
	 * @throws GeneralException
	 */
	public function download( $fileId )
	{
		$result = FileModel::find($fileId, [
			'savename',
			'savepath',
			'ext',
		]);
		if (null === $result) {
			throw new GeneralException('database not exists file record : ' . $fileId, 503002);
		}
		$fileUrl = $result['savepath'] . DIRECTORY_SEPARATOR . $result['savename'];
		if (!file_exists($fileUrl)) {
			throw new GeneralException('server file not exists : ' . $fileUrl, 503010);
		}
		$fp = fopen($fileUrl, "r");
		$filename = $result['savename'] . '.' . $result['ext'];
		$filesize = filesize($fileUrl);
		header("Content-type:application/octet-stream");
		header("Content-Disposition:attachment;filename = " . $filename);
		header("Accept-ranges:bytes");
		header("Accept-length:" . $filesize);
		$buffer = 1024;
		$buffercount = 0;
		while (!feof($fp) && $filesize - $buffercount > 0) {
			$data = fread($fp, $buffer);
			$buffercount += $buffer;
			echo $data;
		}
		fclose($fp);
		if (false === $result) {
			throw new GeneralException('file download fail : ' . var_export($result, true), 503011);
		}
	}
}