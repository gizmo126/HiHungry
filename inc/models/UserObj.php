<?php
include_once 'app/connect.php';

Class User {
    public $user_id;
    public $user_name;
    public $Fname;
    public $Lname;

    public function __construct($id, $name, $f, $l) {
      $this->user_id = $id;
      $this->user_name = $name;
      $this->Fname = $f;
      $this->Lname = $l;
    }
}

Class Cuisine {
    public $cuisine_id;
    public $cuisine_count;

    public function __construct($id, $count) {
      $this->cuisine_id = $id;
      $this->cuisine_count = $count;
    }
}

?>
