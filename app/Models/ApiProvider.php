<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    protected $fillable = [
        'name',
        'network_type',
        'base_url',
        'status_endpoint',
        'status_request_method',
        'request_method',
        'request_headers',
        'request_body',
        'request_body_template',
        'api_key',
        'secret_key',
        'webhook_url',
        'webhook_secret',
        'webhook_allowed_ips',
        'is_active',
        'timeout_seconds',
        'retry_attempts',
        'response_success_field',
        'response_data_field',
        'response_error_field'
    ];

    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'is_active' => 'boolean',
        'timeout_seconds' => 'integer',
        'retry_attempts' => 'integer'
    ];
}
