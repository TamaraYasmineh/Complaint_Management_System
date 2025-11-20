<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['user_id','target', 'code', 'purpose'];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
