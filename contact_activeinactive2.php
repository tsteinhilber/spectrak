<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$edit_contact_edit = $ContactLogin->newEditCommand('sup-Contact',$_REQUEST['-recid']);
$edit_contact_fields = array('conStatus'=>'=='.fmsEscape($_REQUEST['conStatus']),);
foreach($edit_contact_fields as $key=>$value) {
    $edit_contact_edit->setField($key,$value);
}

$edit_contact_result = $edit_contact_edit->execute(); 

if(FileMaker::isError($edit_contact_result)) fmsTrapError($edit_contact_result,"error.php"); 

$edit_contact_row = current($edit_contact_result->getRecords()); 
 
$Contact_row = current($Contact_result->getRecords());

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>

<body>
</body>
</html>
