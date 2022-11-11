<?php

namespace App\Helper;

/**
 * Class EnumClass
 * @package App\Helper\Enum
 */

class EnumClass
{
    public static function getVariables()
    {
        $oClass = new \ReflectionClass(get_called_class());
        return $oClass->getConstants();
    }
}
