<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$edit_record_edit = $ContactLogin->newEditCommand('sup-Contact',$_REQUEST['-recid']);
$edit_record_fields = array('conNameL'=>$_REQUEST['conNameL'],'conNameF'=>$_REQUEST['conNameF'],'conEmail'=>$_REQUEST['conEmail'],'conPhoneDirect'=>$_REQUEST['conPhoneDirect'],'conPhoneExt'=>$_REQUEST['conPhoneExt'],'conTitle'=>$_REQUEST['conTitle'],'con_FK_add'=>$_REQUEST['con_FK_add'],'conStatus'=>$_REQUEST['conStatus'],);
foreach($edit_record_fields as $key=>$value) {
    $edit_record_edit->setField($key,$value);
}

$edit_record_result = $edit_record_edit->execute(); 

if(FileMaker::isError($edit_record_result)) fmsTrapError($edit_record_result,"error.php"); 

$edit_record_row = current($edit_record_result->getRecords()); 
 
if($_REQUEST['x'] == 1) {
	$url = "printer.php?p=" . $_REQUEST['cmp_PK'];
} elseif($_REQUEST['x'] ==2) {
	$url = "supplier.php";
}
fmsRedirect($url); 
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Contact Edit</title>
<link href="css/oneColLiqCtr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColLiqCtr">


<div id="container">
  <div id="mainContent">
    <h1> Supplier Contact Edit</h1>
	<p>cmp_PK: <?php echo $_POST['cmp_PK']; ?></p>
    	<p>First Name: <?php echo $_POST['conNameF']; ?></p>
	<p>Last Name: <?php echo $_POST['conNameL']; ?></p>
      <!-- end #mainContent -->
   </div>
<!-- end #container --></div>
</body>
</html>
