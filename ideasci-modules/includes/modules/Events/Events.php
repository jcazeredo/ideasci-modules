<?php
if (!class_exists('ISM_Events')) {
  class ISM_Events
  {
    public function __construct()
    {
      require_once plugin_dir_path(__FILE__) . "UserAccessControl/UserAccessControl.php";
    }
  }
}

new ISM_Events;
