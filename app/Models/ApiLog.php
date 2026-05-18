<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'provider_id',
        'endpoint',
        'method',
        'status_code',
        'response',
        'error_message'
    ];

    public function provider()
    {
        return $this->belongsTo(ApiProvider::class, 'provider_id');
    }
}
