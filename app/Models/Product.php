<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'ProductID';

    protected $table  = 'products';
    protected $fillable = ['ProductID', 'ProductName', 'ProductTitle', 'ProductPicture', 'ProductType'];

    public $timestamps = false;

       public function tags()
    {
        return $this->hasMany(Tag::class, 'ProductID','ProductID');
    }
}
