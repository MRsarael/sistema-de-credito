<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FinancialInstitution extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'financial_institution';
    protected $fillable = ['id_gosat','name'];
    public $timestamps = true;
}
