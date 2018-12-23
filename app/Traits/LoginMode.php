<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 12/23/18
 * Time: 7:08 PM
 */

namespace App\Traits;


trait LoginMode
{
    protected $NORMAL_MODE = 0;
    protected $RECOVERY_MODE = 1;

    public function someFunction(){
        //Do nothing
    }
}