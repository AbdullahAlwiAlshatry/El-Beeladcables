<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'UserID';
    public $incrementing = true;
    protected $keyType = 'int';

    
    protected $table = 'users';
    protected $fillable = ['email', 'password'];

    public $timestamps = false;

}
