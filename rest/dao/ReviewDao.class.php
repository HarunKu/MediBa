<?php
require_once __DIR__.'/BaseDao.class.php';

class ReviewDao extends BaseDao{
  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("review");
  }

  public function get_review_by_worker_id($id){
    return $this->query("SELECT r.id,r.worker_id,r.user_id,concat(users.user_name,' ',users.user_surname) as user,r.review_grade,r.review_comment,r.posted FROM review r JOIN users on r.user_id=users.id WHERE r.worker_id = :worker_id", ['worker_id' => $id]);
  }
}

?>
