<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$new_address_add = $ContactLogin->newAddCommand('sup-Address');
$new_address_fields = array('add_FK_cmp'=>$_REQUEST['cmp_PK'],'addAddress'=>$_REQUEST['addAddress'],'addCity'=>$_REQUEST['addCity'],'addState'=>$_REQUEST['addState'],'addZip'=>$_REQUEST['addZip'],'addCountry'=>$_REQUEST['addCountry'],);
foreach($new_address_fields as $key=>$value) {
    $new_address_add->setField($key,$value);
}

$new_address_result = $new_address_add->execute(); 

if(FileMaker::isError($new_address_result)) fmsTrapError($new_address_result,"error.php"); 

$new_address_row = current($new_address_result->getRecords()); 
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php
$new_contact_add = $ContactLogin->newAddCommand('sup-Contact');
$new_contact_fields = array('con_FK_cmp'=>$_REQUEST['cmp_PK'],'conNameF'=>$_REQUEST['conNameF'],'conNameL'=>$_REQUEST['conNameL'],'conTitle'=>$_REQUEST['conTitle'],'conEmail'=>$_REQUEST['conEmail'],'conPhoneDirect'=>$_REQUEST['conPhoneDirect'],'conPhoneExt'=>$_REQUEST['conPhoneExt'],'con_FK_add'=>$new_address_row->getField('add_PK'),);
foreach($new_contact_fields as $key=>$value) {
    $new_contact_add->setField($key,$value);
}

$new_contact_result = $new_contact_add->execute(); 

if(FileMaker::isError($new_contact_result)) fmsTrapError($new_contact_result,"error.php"); 

$new_contact_row = current($new_contact_result->getRecords()); 

$newContactID = $new_contact_row->getField('con_PK');
$specID = $_REQUEST["specID"];
$specRecID = $_REQUEST["specRecID"];
$gotoPrinter = $_REQUEST['x'];
$printerURL = "printer.php?p=" . $_REQUEST['cmp_PK'];
$oURL = $_REQUEST['oURL'];

if(specID == "new"){
	$url = $oURL;
} elseif(specID) {
	$url = "spec_edit_contact_response.php?-recid=" . $specRecID . "&contactID=" . $newContactID . "&specID=" . $specID;
} elseif($gotoPrinter == 1) {
	$url = $printerURL;
} elseif($gotoPrinter == 2) {
	$url = "supplier.php";
}
fmsRedirect($url); 
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>new contact &amp; address</title>
<link href="css/oneColLiqCtr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColLiqCtr">


<div id="container">
  <div id="mainContent">
    <h1>Form Data<!-- end #mainContent -->
    </h1>
    <p>cmp_PK: <?php echo $_POST['cmp_PK']; ?></p>
    <p>X: <?php echo $_POST['x']; ?></p>
    <p>URL: <?php echo $url; ?></p>
    <p>conNameF: <?php echo $_POST['conNameF']; ?></p>
    <p>conNameL: <?php echo $_POST['conNameL']; ?></p>
    <p>conTitle: <?php echo $_POST['conTitle']; ?></p>
    <p>conEmail: <?php echo $_POST['conEmail']; ?></p>
    <p>conPhoneDirect: <?php echo $_POST['conPhoneDirect']; ?></p>
    <hr />
    <p>conPhoneExt: <?php echo $_POST['conPhoneExt']; ?></p>
    <p>addAddress: <?php echo $_POST['addAddress']; ?></p>
    <p>addCity: <?php echo $_POST['addCity']; ?></p>
    <p>addState: <?php echo $_POST['addState']; ?></p>
    <p>addZip: <?php echo $_POST['addZip']; ?></p>
    <p>addCountry: <?php echo $_POST['addCountry']; ?></p>
    <h1>New Address</h1>
    <p>add_PK: <?php echo $new_address_row->getField('add_PK'); ?></p>
    <p>Address: <?php echo $new_address_row->getField('addAddress'); ?></p>
    <p>City: <?php echo $new_address_row->getField('addCity'); ?></p>
    <p>State: <?php echo $new_address_row->getField('addState'); ?></p>
    <p>Zip: <?php echo $new_address_row->getField('addZip'); ?></p>
    <p>Country: <?php echo $new_address_row->getField('addCountry'); ?></p>
    <h1>New Contact</h1>
    <p>con_PK: <?php echo $new_contact_row->getField('con_PK'); ?></p>
    <p>con_FK_add: <?php echo $new_contact_row->getField('con_FK_add'); ?></p>
    <p>First Name: <?php echo $new_contact_row->getField('conNameF'); ?></p>
    <p>Last Name: <?php echo $new_contact_row->getField('conNameL'); ?></p>
    <p>Title: <?php echo $new_contact_row->getField('conTitle'); ?></p>
    <p>Email: <?php echo $new_contact_row->getField('conEmail'); ?></p>
    <p>Phone Direct: <?php echo $new_contact_row->getField('conPhoneDirect'); ?></p>
    <p>Phone Ext: <?php echo $new_contact_row->getField('conPhoneExt'); ?></p>
  </div>
<!-- end #container --></div>
</body>
</html>
