<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCourse extends Model
{
    use HasFactory;
    protected $table = 'userscourse';
    protected $fillable = ['id', 'status', 'userID', 'courseID'];
    public $timestamps = false;
}
