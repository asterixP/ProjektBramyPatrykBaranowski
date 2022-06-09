<?php
namespace App\Order\Domain\Exception;

class InvalidInput extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Brak wymaganych pól");
    }
}