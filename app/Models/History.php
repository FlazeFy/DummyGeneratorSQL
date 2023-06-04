<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'is_public', 'generate_type', 'database_id', 'method_id', 'return_type', 'syntax', 'total_rows', 'total_column', 'table_name', 'created_at', 'updated_at', 'deleted_at'];
}
