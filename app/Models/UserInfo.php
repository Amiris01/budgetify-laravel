<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_info';

    protected $fillable = ['user_id', 'first_name', 'last_name', 'birth_date', 'gender', 'phone_number', 'address', 'profile_pic', 'bio'];
}
