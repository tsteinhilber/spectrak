<?php require_once('Connections/ContactLogin.php'); ?>

<?php 
$assigned_string = $_POST['assigned_string'];
$formatted_string = str_replace(" ","\r",$assigned_string);
?>
<?php 
$edit_upc_edit = $ContactLogin->newEditCommand('sup-UPC',$_REQUEST['-recid']);
$edit_upc_fields = array('JobUpcPdk::pdk_FK_lci'=>$assigned_string,);
foreach($edit_upc_fields as $key=>$value) {
    $edit_upc_edit->setField($key,$value);
}

$edit_upc_result = $edit_upc_edit->execute(); 

if(FileMaker::isError($edit_upc_result)) fmsTrapError($edit_upc_result,"error.php"); 

$edit_upc_row = current($edit_upc_result->getRecords()); 

fmsRedirect($_REQUEST['sku']); 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update PDK LCI</title>
</head>
<body>
</body>
</html>
