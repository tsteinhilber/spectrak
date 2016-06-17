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
$edit_record_edit = $ContactLogin->newEditCommand('sup-Contact',$_REQUEST['-recid']);
$edit_record_fields = array('conNameL'=>$_REQUEST['conNameL'],'conNameF'=>$_REQUEST['conNameF'],'conEmail'=>$_REQUEST['conEmail'],'conPhoneDirect'=>$_REQUEST['conPhoneDirect'],'conPhoneExt'=>$_REQUEST['conPhoneExt'],'conTitle'=>$_REQUEST['conTitle'],'con_FK_add'=>$new_address_row->getField('add_PK'),'conStatus'=>$_REQUEST['conStatus'],);
foreach($edit_record_fields as $key=>$value) {
    $edit_record_edit->setField($key,$value);
}

$edit_record_result = $edit_record_edit->execute(); 

if(FileMaker::isError($edit_record_result)) fmsTrapError($edit_record_result,"error.php"); 

$edit_record_row = current($edit_record_result->getRecords());

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
<title>edit contact &amp; address</title>
</head>
<body>

<!--
<h1>Form Data</h1>
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
-->
</body>
</html>
