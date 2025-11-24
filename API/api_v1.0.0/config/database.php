<?php
/**
* Author: César Luna
* Version: v1.0.0
* Github: github.com/Grimmgalohm
*/


$host = $_ENV['DB_HOST'] ?? '';
$db = $_ENV['DB_NAME'] ?? '';
$port = $_ENV['DB_PORT'] ?? 3306;
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

/**
*  Data Source Name
*  URL: https://www.php.net/manual/en/ref.pdo-mysql.connection.php
*/
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

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
