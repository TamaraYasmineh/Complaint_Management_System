<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    protected $fillable = [
        'user_id','action','auditable_type','auditable_id','before','after','ip_address','user_agent'
    ];

    protected $casts = [
        'before' => 'string',
        'after'  => 'string',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
