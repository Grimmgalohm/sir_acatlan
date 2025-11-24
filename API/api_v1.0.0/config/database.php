<?php
/**
* Author: César Luna
* Version: v1.0.0
* Github: github.com/Grimmgalohm
*/


$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$charset = getenv('DB_CHARSET');

/**
*  Data Source Name
*  URL: https://www.php.net/manual/en/ref.pdo-mysql.connection.php
*/
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

/**
* Options for PDO
* No encuentro mucha documentación al respecto, pero esto puede ayudar:
* URL: https://www.php.net/manual/en/class.pdo.php
*/

$options = [
    PDO::ATTR_ERRMODE		 => PDO::ERRMODE_EXCEPTION, //Arroja exepciones en errores SQL (útil para debuggear)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,	    //Arrays asociativos (más fáciles de manejar y gastan menos memoria)
    PDO::ATTR_EMULATE_PREPARES	 => false,		    //Seguridad real
  ];

try{
  return new PDO($dsn, $user, $pass, $options);
} catch(\PDOException $e) {

  error_log($e->getMessage());
  exit('Something went wrong. INFO: <br>' . $e->getMessage());

}


?>
