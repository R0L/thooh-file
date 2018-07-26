<?php

namespace App\Models;

class UserFile extends BaseModel
{
    protected $table = 'users_files';

    protected $fillable = ['user_id', 'file_id', 'times'];

}
