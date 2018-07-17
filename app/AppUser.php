<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $table = 'aar_customer_account';
    protected $primaryKey = 'customer_id';
}
