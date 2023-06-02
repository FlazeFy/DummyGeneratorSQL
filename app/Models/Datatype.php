<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datatype extends Model
{
    use HasFactory;

    protected $table = 'datatypes';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'app_code', 'type_name', 'is_active', 'type_desc', 'created_at', 'updated_at', 'deleted_at'];

    public static function getAllType(){
        $res = Datatype::select('app_code', 'type_name', 'type_desc')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $res;
    }
}
