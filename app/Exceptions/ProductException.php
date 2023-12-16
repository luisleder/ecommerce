<?php

namespace App\Exceptions;

class ProductException extends CustomException
{
    public static function notExist()
    {
        return new self(message: "Product not found.", code: 404);
    }
    
}