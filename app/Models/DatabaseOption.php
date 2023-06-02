<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseOption extends Model
{
    use HasFactory;

    protected $table = 'databases_options';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'app_code', 'database_name', 'database_desc', 'is_active', 'created_at', 'updated_at', 'deleted_at'];

    public static function getAllDBOpt(){
        $res = DatabaseOption::select('app_code', 'database_name', 'database_desc')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $res;
    }
}
