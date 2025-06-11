<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseConnection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function database_type(){
        return $this->belongsTo(DatabaseType::class);
    }
}
