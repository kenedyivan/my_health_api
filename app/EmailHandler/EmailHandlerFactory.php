<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 9/18/18
 * Time: 10:50 AM
 */

namespace App\EmailHandler;


class EmailHandlerFactory
{
    public static function createEmailHandler():iEmailHandler{
        return new EmailHandlerImpl();
    }
}