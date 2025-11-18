<?php

/*
  API SIR Acatlán
  Author: César Luna
  Github: github.com/Grimmgalohm
  Version: 1.0.0
*/

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST') {

 var_dump("This is the method POST");

else{

 header('HTTP/1.1 404 Not Found');
 
 return ?>
  <h1>Oops! Something went wrong... </h1>
  <h2>Error 404</h2>
  <p>The page that you're searching it's not aviable.</p>
 <?php
 
}
?>