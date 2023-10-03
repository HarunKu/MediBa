<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/WorkerDao.class.php';

class WorkerService extends BaseService{

  public function __construct(){
    parent::__construct(new WorkerDao());
  }

  public function get_worker_by_job_id($job_id){
    return $this->dao->get_worker_by_job_id($job_id);
  }

  public function get_workers(){
    return $this->dao->get_workers();
  }
  public function post_worker($user, $worker){

    $data = [
      "worker_job_id" => $worker["worker_job_id"],
      "worker_name" => $worker["worker_name"],
      "worker_city" => $worker["worker_city"],
      "worker_phone_number" => $worker["worker_phone_number"],
      "worker_address" => $worker["worker_address"],
      "worker_email" => $worker["worker_email"],
      "description" => $worker["description"],
      "user_created" => $user["id"]
    ];
    return parent::add($data);


  }
  public function delete_worker($user, $id) {
  $worker = $this->dao->get_by_id($id);
  if($worker["user_created"] != $user["id"] && $user["r"] != "ADMIN") throw new Exception("Can't delete this doctor!", 403);
  return $this->delete($id);
  }

  public function update_worker($id, $data, $user) {
    $worker = $this->dao->get_by_id($id);
    if($worker["user_created"] != $user["id"] && $user["r"] != "ADMIN") return FALSE;
    return $this->update($id, $data);
    }
}
?>
