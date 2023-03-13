<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CreditOfferModality extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'credit_offer_modality';
    protected $fillable = ['id_financial_institution','description','cod'];
    public $timestamps = true;
}
