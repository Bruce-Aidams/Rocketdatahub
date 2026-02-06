<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    protected $fillable = [
        'name',
        'network_type',
        'base_url',
        'request_method',
        'request_headers',
        'request_body',
        'api_key',
        'secret_key',
        'webhook_url',
        'is_active'
    ];

    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'is_active' => 'boolean'
    ];
}
