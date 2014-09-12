<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAssertions
 *
 * @author fabrice
 */
class ModelAssertions extends PAF_Assert
{

    public static function str($value, $paramName = 'value')
    {
        self::_(is_string($value), $paramName . ' must be STRING');
    }

    public static function ref($value, $paramName = 'value')
    {
        self::_(preg_match('^[A-Za-z0-9_+-]{1,45}$', $value), $paramName . ' must have REFERENCE format');
    }

    public static function notEmptyStr($value, $paramName = 'value')
    {
        self::str($value, $paramName);
        self::_(strlen(trim($value) > 1), $paramName . ' must NOT be empty');
    }

    public static function num($value, $paramName = 'value')
    {
        self::_(is_numeric($value), $paramName . ' must be NUMERIC');
    }

    public static function bool($value, $paramName = 'value')
    {
        self::_(is_bool($value), $paramName . ' must be BOOLEAN');
    }

}
