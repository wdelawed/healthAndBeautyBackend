<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'age', 'before_img', 'after_img' , 'notes'
    ];

    protected $attributes = [
        'prescriptions' => null ,
    ];

    public function prescriptions(){
        return $this->belongsToMany(Prescription::class, 'customer_prescriptions')->withTimestamps() ;
    }
}
