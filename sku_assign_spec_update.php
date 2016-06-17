<?php require_once('Connections/ContactLogin.php'); ?>

<?php
$assigned_string = $_POST['upc_FK_spe'];
$formatted_string = str_replace("/","\r",$assigned_string);
?>

<?php
$edit_record_edit = $ContactLogin->newEditCommand('sup-UPC',$_REQUEST['-recid']);
$edit_record_fields = array('upc_FK_spe'=>$formatted_string,);
foreach($edit_record_fields as $key=>$value) {
    $edit_record_edit->setField($key,$value);
}

$edit_record_result = $edit_record_edit->execute(); 

if(FileMaker::isError($edit_record_result)) fmsTrapError($edit_record_result,"error.php"); 

$edit_record_row = current($edit_record_result->getRecords()); 

fmsRedirect($_REQUEST['sku']);

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update UPC Spec</title>
</head>

<body>
<p><strong>UPC_FK_spe:</strong> <?php print_r($_POST['upc_FK_spe']); ?></p>
<p><strong>Job_PK:</strong> <?php echo $_POST['job_PK']; ?></p>
<p><strong>UPC_PK:</strong> <?php echo $_POST['upc_PK']; ?></p>
<p><strong>RecID:</strong> <?php echo $_POST['-recid']; ?></p>
</body>
</html>
