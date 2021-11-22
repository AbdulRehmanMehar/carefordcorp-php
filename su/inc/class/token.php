<?php
session_start();
class Token{
  public static function generate(){
    return  $_SESSION['token']  = base64_encode(openssl_random_pseudo_bytes(32));
  }
  public static function check($param){
    if (isset($_SESSION['token']) && $param === $_SESSION['token']) {
        unset($_SESSION['token']);
        return true;
    }
    return false;
  }
}
