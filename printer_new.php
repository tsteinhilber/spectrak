<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1);

$Supplier_result = $Supplier_find->execute();

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php");

fmsSetLastPage($Supplier_result,'Supplier',1);

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');

 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

 <?php

 if($_REQUEST['oURL']){
	$oURL = $_REQUEST['oURL'];
} else {
	$oURL = $_SERVER['HTTP_REFERER'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Create New Printer</title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css">
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->

<link rel="icon" type="image/gif" href="img/favicon.gif">

<!-- CSS -->
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script language="JavaScript">

	$(document).ready(function(){
		$("input:text:visible:first").focus();
	});

	function validateOnSubmit(){
		var myForm = document.getElementById("printer_new");
		var SS = Spry.Widget.Form.validate(myForm);
		if(SS == true){
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues saving this printer", "Please enter valid data into the fields highlighted in red");
			highlightErrors();
		}
	}

</script>

</head>

<body class="twoColHybLtHdr">


<div id="container">

<!-- HEADER -->
<div id="header">
	<div id="logo">
		<ul id="clientscape_header" class="headerLogo">
			<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
		</ul>
	</div>
	<h1>Create New Printer</h1>
</div>

<!-- SIDEBAR -->
<div id="sidebar1">

	<ul id="supplier" class="MenuBarVertical">
		<li><a href="index.php">Home</a></li>
		<li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
		<li><a href="supplier.php">Update Company Contact Info</a></li>
		<li><a href="jobs.php">Jobs</a></li>
		<li><a href="sku_list.php">SKU List</a></li>
	</ul>

	<h1>Actions</h1>
	<ul id="actions" class="MenuBarVertical">
		<li><a onclick="validateOnSubmit()">Save New Printer</a></li>
		<li><a href="javascript:history.back()">Back</a></li>
	</ul>

</div>

<!-- MAIN CONTENT -->
  <div id="mainContent">

	<!-- INFO PANEL -->
	<div id="infoPanel" class="">
    <?php
      if($_SESSION['conName'] == 'John Supplier'){
        echo "<h2>origin: <a href'" . $_SESSION['origin'] . "'>" . $_SESSION['origin'] . "</a></h2>";
      }
    ?>
  </div>

	<span class="callout">Instructions:</span> Once the new printer is created, you will be able to add its contacts and locations.
    <form action="printer_new_response.php" method="post" name="printer_new" id="printer_new">
      <table id="new_printer" class="table" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td class="table_side_head_req">Company Name</td>
          <td class="table_row">
          	<span id="sprytextfield1">
            	<input name="cmpCompany" type="text" id="cmpCompany">
          	</span>
          </td>
        </tr>
        <tr>
          <td class="table_side_head">Website URL</td>
          <td class="table_row">
          	<span id="sprytextfield3">
          		<input name="cmpWebsite" type="text" id="cmpWebsite">
				<span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span>
			</span>
		  </td>
        </tr>
      </table>
      <input type="button" id="btn_save" value="Save" onclick="validateOnSubmit()">
	  <input type="hidden" name="oURL" value="<?php echo $oURL; ?>" id="oURL">
    </form>
</div>
	<!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats -->
<br class="clearfloat" />
	<div id="footer">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" valign="MIDDLE">© Galileo Global Branding Group, Inc.</td>
        <td valign="MIDDLE" height="25">&nbsp;</td>
        <td valign="MIDDLE" height="25" width="200"></td>
        <td valign="MIDDLE" height="25" width="50"><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></td>
      </tr>
    </table>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["change"], isRequired:false});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
