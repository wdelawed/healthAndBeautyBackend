<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'notes', 'creation_date', 'price' ,'presc_image'
    ];

    public function customers(){
        return $this->belongsToMany(Customer::class, 'customer_prescriptions')->withTimestamps() ;
    }

    public function components(){
        return $this->belongsToMany(Component::class, 'prescription_components') ;
    }
}
