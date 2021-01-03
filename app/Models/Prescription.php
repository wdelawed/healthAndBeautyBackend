<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'notes', 'creation_date', 'price' 
    ];

    public function components(){
        return $this->belongsToMany('App\Models\Component', 'prescription_component') ;
    }
}
