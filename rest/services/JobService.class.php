<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/JobDao.class.php';

class JobService extends BaseService{

  public function __construct(){
    parent::__construct(new JobDao());
  }

}
?>
