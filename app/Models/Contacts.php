<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'fname',
        'lname',
        'email',
        'phone',
        'job',
        'education',
        'address',
        'city',
        'language',
        'cv',
        'dob',
        'user_id',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
