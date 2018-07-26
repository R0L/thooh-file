<?php

namespace App\Models;


class File extends BaseModel
{
    protected $table = 'files';
	
	public $timestamps = false;
    
    protected $fillable = ['name', 'savename', 'savepath', 'ext', 'mime', 'size', 'md5', 'sha1', 'location', 'create_time'];
}
