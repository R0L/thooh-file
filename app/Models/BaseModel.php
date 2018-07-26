<?php
/**
 * @desc   PhpStorm.
 * @author thooh
 * @date   2018/7/26
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	public $timestamps = true;
	
	protected $dateFormat = 'U';
    
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $fillable = [];
    protected $guarded = [];

}