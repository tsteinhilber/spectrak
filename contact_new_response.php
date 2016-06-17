<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$new_contact_add = $ContactLogin->newAddCommand('sup-Contact');
$new_contact_fields = array('con_FK_cmp'=>$_REQUEST['cmp_PK'],'conNameF'=>$_REQUEST['conNameF'],'conNameL'=>$_REQUEST['conNameL'],'conTitle'=>$_REQUEST['conTitle'],'conEmail'=>$_REQUEST['conEmail'],'conPhoneDirect'=>$_REQUEST['conPhoneDirect'],'conPhoneExt'=>$_REQUEST['conPhoneExt'],'con_FK_add'=>$_REQUEST['con_FK_add'],);
foreach($new_contact_fields as $key=>$value) {
    $new_contact_add->setField($key,$value);
}

$new_contact_result = $new_contact_add->execute();

if(FileMaker::isError($new_contact_result)) fmsTrapError($new_contact_result,"error.php");

$new_contact_row = current($new_contact_result->getRecords());

$newContactID = $new_contact_row->getField('con_PK');
$newContactName = $new_contact_row->getField('conNameF') . " " . $new_contact_row->getField('conNameL');
$successMessage = $newContactName . " created";
$specID = $_REQUEST["specID"];
$specRecID = $_REQUEST["specRecID"];
$gotoPrinter = $_REQUEST['x'];
$printerURL = "printer.php?p=" . $_REQUEST['cmp_PK'];
$oURL = $_REQUEST['oURL'];
$referer = $_SERVER['HTTP_REFERER'];

if($specID == "new"){
	$url = $referer;
} elseif($specID == "" && $gotoPrinter == 1) {
	$url = $printerURL;
} elseif($specID == "" && $gotoPrinter == 2) {
	$url = "supplier.php";
} elseif($specID != "new") {
	$url = "spec_edit_contact_response.php?-recid=" . $specRecID . "&contactID=" . $newContactID . "&specID=" . $specID;
}

//fmsRedirect($url);

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>New Contact Response</title>
<script type="text/javascript">
	function redirect(){
		 document.returnInfo.submit();
	}
</script>
</head>

<body onload="redirect();">
	<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $url; ?>">
	  <input id="oURL" name="oURL" type="hidden" value="<?php echo $oURL; ?>">
	  <input id="newContactID" name="newContactID" type="hidden" value="<?php echo $newContactID; ?>">
	  <input id="infoReturned" name="infoReturned" type="hidden" value="<?php echo $successMessage; ?>">
	  <input id="infoPassFail" name="infoPassFail" type="hidden" value="pass">
	</form>
</body>
</html>
