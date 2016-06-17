<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Contact_find = $ContactLogin->newFindCommand('sup-Contact');
$Contact_findCriterions = array('con_PK'=>'=='.fmsEscape($_REQUEST['con_PK']),);
foreach($Contact_findCriterions as $key=>$value) {
    $Contact_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contact_find,'Contact',1); 

$Contact_result = $Contact_find->execute(); 

if(FileMaker::isError($Contact_result)) fmsTrapError($Contact_result,"error.php"); 

fmsSetLastPage($Contact_result,'Contact',1); 

$Contact_row = current($Contact_result->getRecords());

$Contact__SupJobCmpPRINTERConAdd_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTERConAdd');
$Contact__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTER');
$Contact__Sup_portal = fmsRelatedRecord($Contact_row, 'Sup');
$Contact__calc_portal = fmsRelatedRecord($Contact_row, 'calc');
$Contact__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTERAdd');

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php
$recID = $Contact_row->getRecordId();

$edit_contact_edit = $ContactLogin->newEditCommand('sup-Contact',$recID);
$edit_contact_fields = array('conStatus'=>$_REQUEST['conStatus'],);
foreach($edit_contact_fields as $key=>$value) {
    $edit_contact_edit->setField($key,$value);
}

$edit_contact_result = $edit_contact_edit->execute(); 

if(FileMaker::isError($edit_contact_result)) fmsTrapError($edit_contact_result,"error.php"); 

$edit_contact_row = current($edit_contact_result->getRecords()); 

fmsRedirect(-1); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>

<body>
<?php echo $Contact_row->getField('conNameF'); ?> <?php echo $Contact_row->getField('conNameL'); ?> | <?php echo $Contact_row->getRecordId(); ?>
</body>
</html>
