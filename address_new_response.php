<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$new_record_add = $ContactLogin->newAddCommand('sup-Address');
$new_record_fields = array('add_FK_cmp'=>$_REQUEST['cmp_PK'],'addAddress'=>$_REQUEST['addAddress'],'addCity'=>$_REQUEST['addCity'],'addState'=>$_REQUEST['addState'],'addZip'=>$_REQUEST['addZip'],'addCountry'=>$_REQUEST['addCountry'],);
foreach($new_record_fields as $key=>$value) {
    $new_record_add->setField($key,$value);
}

$new_record_result = $new_record_add->execute(); 

if(FileMaker::isError($new_record_result)) fmsTrapError($new_record_result,"error.php"); 

$new_record_row = current($new_record_result->getRecords()); 

if($_REQUEST['x'] == 1) {
	$url = "printer.php?p=" . $_REQUEST['cmp_PK'];
} elseif($_REQUEST['x'] == 2) {
	$url = "supplier.php";
}
fmsRedirect($url); 
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
<link href="css/oneColLiqCtr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColLiqCtr">


<div id="container">
  <div id="mainContent">
    <h1> Location New
      <!-- end #mainContent -->
    </h1>
    <p>cmp_PK: <?php echo $_POST['cmp_PK']; ?></p>
    <p>First Name: <?php echo $_POST['addAddress']; ?></p>
  </div>
<!-- end #container --></div>
</body>
</html>
