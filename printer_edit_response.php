<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$edit_printer_edit = $ContactLogin->newEditCommand('sup-Printer',$_REQUEST['-recid']);
$edit_printer_fields = array('cmpWebsite'=>$_REQUEST['cmpWebsite'],);
foreach($edit_printer_fields as $key=>$value) {
    $edit_printer_edit->setField($key,$value);
}

$edit_printer_result = $edit_printer_edit->execute(); 

if(FileMaker::isError($edit_printer_result)) fmsTrapError($edit_printer_result,"error.php"); 

$edit_printer_row = current($edit_printer_result->getRecords()); 

// fmsRedirect(-1);
$redirect = $_SERVER['HTTP_REFERER'];
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Printer edit response</title>

<script type="text/javascript">
	function redirect(){
		document.returnInfo.submit();
	}
</script>

</head>

<body onload="redirect()">

	<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $redirect; ?>">
	  <input id="infoReturned" name="infoReturned" type="hidden" value="<?php echo $edit_printer_row->getField('cmpCompany'); ?> updated">
	  <input id="infoPassFail" name="infoPassFail" type="hidden" value="pass">
	</form>

</body>
</html>
