<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogApi extends Model
{
    use HasFactory;
    protected $table = 'log_api';
    protected $fillable = ['body', 'response', 'code', 'client'];
    public $timestamps = true;
}
