<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'aar_service_requests';
    protected $primaryKey = 'service_request_id';
}
