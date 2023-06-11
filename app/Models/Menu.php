<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'sort_number', 'is_protected', 'protected_key', 'menu_group', 'menu_name', 'menu_url', 'menu_image_path', 'is_active', 'menu_desc', 'created_at', 'updated_at', 'deleted_at'];

    public static function getAllMenu(){
        $res = Menu::selectRaw('is_protected, menu_group, menu_name, menu_url, menu_image_path, menu_desc, CASE WHEN SUM(total_rows) IS NOT NULL THEN SUM(total_rows) ELSE 0 END AS total_dummy')
            ->leftJoin('histories','menus.menu_name','=','histories.method_id')
            ->where('is_active', 1)
            ->whereNull('menus.deleted_at')
            ->groupBy('is_protected', 'menu_group', 'menu_name', 'menu_url', 'menu_image_path', 'menu_desc')
            ->orderBy('sort_number', 'DESC')
            ->orderBy('menus.updated_at', 'DESC')
            ->get();

        return $res;
    }
}
