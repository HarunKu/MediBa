<?php
require_once __DIR__.'/BaseDao.class.php';

class UserDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("users");
  }

  public function get_user_by_email($email){
    return $this->query_unique("SELECT * FROM users WHERE email = :email", ['email' => $email]);
  }

  public function get_user_by_id($id){
    return $this->query_unique("SELECT * FROM users WHERE id = :id", ['id' => $id]);
  }

  public function get_user_by_phone_number($phone){
    return $this->query_unique("SELECT * FROM users WHERE phone = :phone", ['phone' => $phone]);
  }

  public function get_user_by_token($token)
    {
        return $this->query_unique("SELECT * FROM users WHERE token = :token", ["token" => $token]);
    }
  public function add($entity){
    return parent::add([
        "password" => md5($entity['password']),
        "user_name" => $entity['user_name'],
        "user_surname" => $entity['user_surname'],
        "city" => $entity['city'],
        "email" =>$entity['email'],
        "phone" =>$entity['phone'],
        "token" => md5(random_bytes(16))
      ]);

  }
  public function confirm($token)
  {
      $user = $this->get_user_by_token($token);
      if (!isset($user['id'])) throw new Exception("Invalid token", 400);
      $this->update($user['id'], ["status" => "ACTIVE", "token" => NULL]);

      return $user;
  }
  public function change_password($user, $password){

    return parent::update($user,["password" => md5($password)]);

  }
  public function update_user($id,$entity){
    return parent::update($id,[
        "user_name" => $entity['user_name'],
        "user_surname" => $entity['user_surname'],
        "city" => $entity['city'],
        "email" =>$entity['email'],
        "phone" =>$entity['phone']
      ]);

  }

}

?>
