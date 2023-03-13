<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PersonalCreditOffer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'personal_credit_offer';
    protected $fillable = ['id_person','id_credit_offer_modality'];
    public $timestamps = true;
}
