<?php

class Config {

  public static function DB_HOST(){
    return Config::get_env("DB_HOST", "localhost");
  }
  public static function DB_USERNAME(){
    return Config::get_env("DB_USERNAME", "root");
  }
  public static function DB_PASSWORD(){
    return Config::get_env("DB_PASSWORD", "0000");
  }
  public static function DB_SCHEME(){
    return Config::get_env("DB_SCHEME", "mediba");
  }
  public static function DB_PORT(){
    return Config::get_env("DB_PORT", "3307");
  }
  public static function JWT_SECRET(){
    return Config::get_env("JWT_SECRET", "ezcb9s8UcF");
  }
  public static function SMTP_PORT(){
    return Config::get_env("SMTP_PORT", "");
  }
  public static function SMTP_USERNAME(){
    return Config::get_env("SMTP_USERNAME", "");
  }
  public static function SMTP_HOST(){
    return Config::get_env("SMTP_HOST", "");
  }
  public static function SMTP_ENCRIPTION(){
    return Config::get_env("SMTP_ENCRIPTION", "");
  }
  public static function SMTP_PASSWORD(){
    return Config::get_env("SMTP_PASSWORD", "");
  }

  public static function get_env($name, $default){
   return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
  }
}

?>
