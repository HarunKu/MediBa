<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/ReviewDao.class.php';

class ReviewService extends BaseService{

  public function __construct(){
    parent::__construct(new ReviewDao());
  }

  public function get_review_by_worker_id($worker_id){
    return $this->dao->get_review_by_worker_id($worker_id);
  }
  public function post_review($user, $review){

    $data = [
      "worker_id" => $review["worker_id"],
      "user_id" => $user["id"],
      "review_grade" => $review["review_grade"],
      "review_comment" => $review["review_comment"]
    ];
    return parent::add($data);


  }
  public function delete_review($user, $id) {
  $review = $this->dao->get_by_id($id);
  if($review["user_id"] != $user["id"] && $user["r"] != "ADMIN") throw new Exception("Can't delete this review!", 403);
  return $this->delete($id);
  }
}
?>
