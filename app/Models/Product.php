<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'prd_id';

    protected $fillable = [
        'prd_name',  
        'prd_description',  
        'prd_price', 
        'prd_image'
    ];
}
