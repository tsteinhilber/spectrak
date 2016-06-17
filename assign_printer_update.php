<?php require_once('Connections/ContactLogin.php'); ?>
<?php

// ASSIGNED STRING
$assigned_string = $_POST['assigned_string'];
$assigned_string_arr = explode("/", $assigned_string, -1);

// JOB_FK_VEN
$job_FK_ven = $_POST['job_FK_ven'];
$job_FK_ven = preg_replace ( "/\s\s+/" , " " , $job_FK_ven ) ;
$job_FK_ven_arr = explode(" ", $job_FK_ven);

// JOB_FK_VNC
$job_FK_vnc = $_POST['job_FK_vnc'];
$job_FK_vnc = preg_replace ( "/\s\s+/" , " " , $job_FK_vnc ) ;
$job_FK_vnc_arr = explode(" ", $job_FK_vnc);

// GET UNIQUE VALUES FOR JOB_FK_VEN
$result_ven = array_merge($assigned_string_arr, $job_FK_ven_arr);
$unique_ven = array_unique($result_ven);
$unique_str_ven = implode("\r", $unique_ven);

// GET UNIQUE VALUES FOR JOB_FK_VNC
$result_vnc = array_merge($assigned_string_arr, $job_FK_vnc_arr);
$unique_vnc = array_unique($result_vnc);
$unique_str_vnc = implode("\r", $unique_vnc);


$edit_job_edit = $ContactLogin->newEditCommand('sup-Job Detail',$_REQUEST['-recid']);
$edit_job_fields = array('job_FK_ven'=>$unique_str_ven,'job_FK_vnc'=>$unique_str_vnc,);
foreach($edit_job_fields as $key=>$value) {
    $edit_job_edit->setField($key,$value);
}

$edit_job_result = $edit_job_edit->execute();

if(FileMaker::isError($edit_job_result)) fmsTrapError($edit_job_result,"error.php");

$edit_job_row = current($edit_job_result->getRecords());

fmsRedirect($_SESSION['origin']);

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update Job Printer Assignments</title>
</head>

<body>
<h2>Update Job Printer Assignments</h2>
<p><strong>assigned string:</strong> <?php echo $assigned_string; ?></p>
<p><strong>job_FK_ven:</strong> <?php echo $job_FK_ven ; ?></p>
<br>
<p><strong>assigned string array:</strong> <?php var_dump($assigned_string_arr); ?></p>
<p><strong>job_FK_ven array:</strong> <?php var_dump($job_FK_ven_arr); ?></p>
<br>
<p><strong>unique:</strong> <?php var_dump($unique); ?></p>
<p><strong>unique_str:</strong> <?php echo $unique_str; ?></p>
</body>
</html>
