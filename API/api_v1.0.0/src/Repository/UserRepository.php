<?php
namespace App\Repository;

use PDO;
use App\Model\User;

class UserRepository {

    public function __construct(private PDO $db){}
        
    public function findByEmail(string $email): ?User {

    $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE email = :email ");
    $stmt->bindValue(':email', $email);

    $stmt->execute();

    $data = $stmt->fetch();

    if(!$data){

      return null;

    }
    
    return new User(
      $data['id'],
      $data['name'],
      $data['email']
    );

  }

  public function create(string $name, string $email, string $passwordHash): bool{
    $stmt = $this->db->prepare("INSERT INTO users(name, email, password) VALUES(:name, :email, :pass)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':pass', $passwordHash);

    return $stmt->execute();
  }

}


?>
