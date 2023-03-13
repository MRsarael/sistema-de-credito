<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'simulation';
    protected $fillable = ['id_personal_credit_offer', 'min_installments', 'max_installments', 'min_value', 'max_value', 'month_interest'];
    public $timestamps = true;
}
