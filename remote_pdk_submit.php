<?php require_once('Connections/ContactLogin.php'); ?>

<!--FIND UPC-->

<?php
$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
$UPC_findCriterions = array('upc_PK'=>'=='.fmsEscape($_REQUEST['u']),);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

fmsSetPage($UPC_find,'UPC',1); 

$UPC_result = $UPC_find->execute(); 

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 

fmsSetLastPage($UPC_result,'UPC',1); 

$UPC_row = current($UPC_result->getRecords());

$UPC__JOB_portal = fmsRelatedRecord($UPC_row, 'JOB');

$rec_id = $UPC_row->getRecordId();
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!--UPDATE UPC-->

<?php
$edit_upc_edit = $ContactLogin->newEditCommand('sup-UPC-assign',$rec_id);
$edit_upc_fields = array('upcPDKStatus'=>'Submitted',);
foreach($edit_upc_fields as $key=>$value) {
    $edit_upc_edit->setField($key,$value);
}

$edit_upc_result = $edit_upc_edit->execute(); 

if(FileMaker::isError($edit_upc_result)) fmsTrapError($edit_upc_result,"error.php"); 

$edit_upc_row = current($edit_upc_result->getRecords()); 

$edit_upc_edit->SetScript('trigger [update job_SpecStatus]',$_REQUEST['u']);

fmsRedirect(-1); 
 
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
