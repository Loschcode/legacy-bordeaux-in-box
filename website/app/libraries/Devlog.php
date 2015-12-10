<?php

/**
 * Devlog
 * by Laurent Schaffner
 */

class Devlog {

    protected static $content = [];

    public static function info($string)
    {

      self::$content[] = [

        "type" => "info",
        "data" => $string

      ];

    }


    public static function strong($string)
    {

      self::$content[] = [

        "type" => "strong",
        "data" => $string
        
      ];

    }


    public static function error($string)
    {

      self::$content[] = [

        "type" => "error",
        "data" => $string
        
      ];

    }

    public static function success($string)
    {

      self::$content[] = [

        "type" => "success",
        "data" => $string
        
      ];

    }

    public static function light($string)
    {

      self::$content[] = [

        "type" => "light",
        "data" => $string
        
      ];

    }


    public static function result()
    {

      return self::$content;

    }

}