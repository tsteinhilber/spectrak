<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$new_record_add = $ContactLogin->newAddCommand('sup-Printer');
$new_record_fields = array('cmpCompany'=>$_REQUEST['cmpCompany'],'cmpFlagCreatedFromWeb'=>'1', 'cmpType'=>'Vendor','cmpVenCategory'=>'Printing','cmpWebsite'=>$_REQUEST['cmpWebsite'],);
foreach($new_record_fields as $key=>$value) {
    $new_record_add->setField($key,$value);
}

$new_record_result = $new_record_add->execute(); 

if(FileMaker::isError($new_record_result)) fmsTrapError($new_record_result,"error.php"); 

$new_record_row = current($new_record_result->getRecords()); 

$new_record_row = current($new_record_result->getRecords());

// fmsRedirect('printer.php?p=' . $new_record_row->getField('cmp_PK')); 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php

$oURL = $_REQUEST['oURL'];
fmsRedirect($oURL); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>New Printer</title>
</head>

<body>

<div id="container">
  <div id="mainContent">
    <h1> New Printer</h1>
    <h2>form parameters</h2>
    <p>u: <?php echo $_POST['u']; ?></p>
    <p>jobrecid: <?php echo $_POST['jobrecid']; ?></p>
    <p>Job Name: <?php echo $_POST['jobNameWbnd']; ?></p>
    <p>Company Name: <?php echo $_POST['cmpCompany']; ?></p>
    <p>Phone Main: <?php echo $_POST['cmpPhoneMain']; ?></p>
    <p>Fax: <?php echo $_POST['cmpFax']; ?></p>
    <p>job_FK_ven: <?php echo $_POST['job_FK_ven']; ?></p>
    <p>job_FK_ven VAR: <?php echo $job_FK_ven; ?></p>
    <p>formatted_string VAR: <?php echo $formatted_string; ?></p>
    <p>
      <textarea name="textarea" id="textarea" cols="45" rows="5"><?php echo $formatted_string; ?></textarea>
    </p>
    <h2>New Printer Record</h2>
    <p>Name: <?php echo $new_record_row->getField('cmpCompany'); ?></p>
    <p>Phone: <?php echo $new_record_row->getField('cmpPhoneMain'); ?></p>
    <p>Fax: <?php echo $new_record_row->getField('cmpFax'); ?></p>
    <p>Flag: <?php echo $new_record_row->getField('cmpFlagCreatedFromWeb'); ?></p>
    <p>Vendor Category: <?php echo $new_record_row->getField('cmpVenCategory'); ?></p>
    <p>Created By: <?php echo $new_record_row->getField('zdevCreatedBy'); ?></p>
    <p>TimeStamp: <?php echo $new_record_row->getField('zdevCreatedTimeStamp'); ?></p>
    <h2>cmp_PK: <?php echo $new_record_row->getField('cmp_PK'); ?></h2>
  </div>
</div>
</body>
</html>
