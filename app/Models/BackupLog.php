<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $guarded = [];

    use HasFactory;

    public function database_connection(){
        return $this->belongsTo(DatabaseConnection::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
