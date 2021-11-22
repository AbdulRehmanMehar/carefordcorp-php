<?php
if (isset($_SERVER['REQUEST_METHOD']) == "POST") {
  session_start();
  session_unset();
  $out = session_destroy();
  if($out){header("Location: https://carefordcorp.com/su?msg=You%20are%20logged%20out%20successfully");}
}else{
  header("Location: https://carefordcorp.com/su/?msg=You%20are%20not%20authorized");
}
