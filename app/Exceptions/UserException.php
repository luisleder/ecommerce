<?php

namespace App\Exceptions;

class UserException extends CustomException
{
    public static function notExists()
    {
        return new self(message: "User not exist", code: 404);
    }
}