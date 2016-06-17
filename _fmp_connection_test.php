<?php require_once('Connections/connection_test.php'); ?>
<?php
$found_records_find = $connection_test->newFindCommand('sup-conLogin');
$found_records_findCriterions = array('con_PK'=>'con106779',);
foreach($found_records_findCriterions as $key=>$value) {
    $found_records_find->AddFindCriterion($key,$value);
}

fmsSetPage($found_records_find,'found_records',1); 

$found_records_result = $found_records_find->execute(); 

if(FileMaker::isError($found_records_result)) fmsTrapError($found_records_result,"error.php"); 

fmsSetLastPage($found_records_result,'found_records',1); 

$found_records_row = current($found_records_result->getRecords());

$found_records__Sup_portal = fmsRelatedRecord($found_records_row, 'Sup');
$found_records__calc_portal = fmsRelatedRecord($found_records_row, 'calc');
$found_records__SupJobCmpPRINTER_portal = fmsRelatedRecord($found_records_row, 'SupJobCmpPRINTER');
$found_records__SupJob_portal = fmsRelatedRecord($found_records_row, 'SupJob');

$_SESSION['con_ID'] = $found_records_row->getField('con_PK');

 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Connection Test</title>
</head>
Found: <?php echo $found_records_row->getField('con_PK'); ?>
<body>
</body>
</html>
