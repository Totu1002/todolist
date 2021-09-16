<?php
class RecordLogging{
	public function record_logging($log_msg){
    $now = date("Y-m-d H:i:s");
	  file_put_contents('./log/todolist.log',$now . " " . $log_msg . "\n",FILE_APPEND);
	}
}
//$log_msg = "success";
//$logging = new RecordLogging;
//$logging->record_logging($log_msg);
?>