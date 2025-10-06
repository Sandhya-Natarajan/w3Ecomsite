<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;



class UserProfile extends Model
{
    //

    protected $fillable = ['user_id', 'phone', 'city', 'country', 'address','role'];


    // Relationship with User
    public function user()
    {
        return $this->belongsTo(related: User::class);
    }



}
