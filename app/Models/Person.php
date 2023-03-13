<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'person';
    protected $fillable = ['name','cpf','age','email'];
    public $timestamps = true;
}
