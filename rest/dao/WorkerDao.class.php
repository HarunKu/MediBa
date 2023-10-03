<?php
require_once __DIR__.'/BaseDao.class.php';

class WorkerDao extends BaseDao{
  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("worker");
  }

  public function get_workers(){
    return $this->query("SELECT w.id,w.worker_name,w.worker_city,w.worker_phone_number,w.worker_email,w.worker_address,w.description,j.job_name,
    concat(u.user_name,' ',u.user_surname) as user,w.user_created, COALESCE(ROUND(AVG(r.review_grade),2),0) as avg
    FROM worker w JOIN job j on j.id=w.worker_job_id JOIN users u on u.id=w.user_created LEFT JOIN review r on r.worker_id=w.id 
    GROUP BY w.id ORDER BY avg DESC",[]);
  }

  public function get_worker_by_job_id($id){
    return $this->query("SELECT w.id,w.worker_name,w.worker_city,w.worker_phone_number,w.worker_email,w.worker_address,w.description,j.job_name,
    concat(u.user_name,' ',u.user_surname) as user,w.user_created, COALESCE(ROUND(AVG(r.review_grade),2),0) as avg 
    FROM worker w JOIN job j on j.id=w.worker_job_id JOIN users u on u.id=w.user_created LEFT JOIN review r on r.worker_id=w.id 
    WHERE w.worker_job_id = :job_id 
    GROUP BY w.id ORDER BY avg DESC", ['job_id' => $id]);
  }

}

?>
