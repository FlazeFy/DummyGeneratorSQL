<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factories extends Model
{
    use HasFactory;

    protected $table = 'factories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'datatype_id', 'app_code', 'factory_name', 'factory_desc', 'is_active', 'created_at', 'updated_at', 'deleted_at'];

    public static function getFactoryByIdType($dt){
        $res = Factories::select('app_code', 'factory_name', 'factory_desc')
            ->where('datatype_id', $dt)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('factory_name', 'ASC')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $res;
    }
}
