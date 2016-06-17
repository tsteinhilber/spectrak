<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Address_find = $ContactLogin->newFindCommand('sup-Address');
$Address_findCriterions = array('add_PK'=>$_REQUEST['a'],);
foreach($Address_findCriterions as $key=>$value) {
    $Address_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Address_find,'Address',1); 

$Supplier_result = $Supplier_find->execute(); 

$Address_result = $Address_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($Address_result)) fmsTrapError($Address_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($Address_result,'Address',1); 

$Address_row = current($Address_result->getRecords());

$Address__calc_portal = fmsRelatedRecord($Address_row, 'calc');
$Address__Sup_portal = fmsRelatedRecord($Address_row, 'Sup');
 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');

$printer = $_SERVER['HTTP_REFERER'];

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | <?php echo $Address_row->getField('Sup::cmpCompany'); ?> Address: <?php echo $Address_row->getField('addAddress'); ?></title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->

<link rel="icon" type="image/gif" href="img/favicon.gif">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script type="text/javascript">
	
	function validateOnSubmit(){
		var myForm = document.getElementById("edit_address");
		var SS = Spry.Widget.Form.validate(myForm);
		if(SS == true){
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues saving this address", "Please enter valid data into the fields highlighted in red");
			highlightErrors();
		}
	}
	
  </script>

</head>

<body class="twoColHybLtHdr">


<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a onclick="linkOutAlert('index.php');"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1><?php echo $Address_row->getField('Sup::cmpCompany'); ?> Address</h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a onclick="linkOutAlert('index.php');">Home</a></li>
    <li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>');">Logout</a></li>
	<li><a onclick="linkOutAlert('supplier.php');">Update Company Contact Info</a></li>
    <li><a onclick="linkOutAlert('jobs.php');">Jobs</a></li>
  </ul>

<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">
	<li><a onclick="javascript:validateOnSubmit();">Save</a></li>
	<li><a onclick="linkOutAlert('<? echo $printer; ?>');">Cancel</a></li>
</ul>
<!-- end #sidebar1 --></div>
  
  <div id="mainContent">

	<!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>

    <form id="edit_address" name="edit_address" method="post" action="address_edit_response.php">
    <input name="-recid" type="hidden" value="<?php echo $Address_row->getRecordId(); ?>" id="-recid">
    <input name="x" type="hidden" value="1" id="x">
    <input name="cmp_PK" type="hidden" value="<?php echo $Address_row->getField('add_FK_cmp'); ?>" id="cmp_PK">
  <span id="sprytextfield1">      </span>
  <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="table_side_head_req">Address</td>
      <td class="table_row"><span id="sprytextfield3">
        <input name="addAddress" type="text" value="<?php echo $Address_row->getField('addAddress'); ?>" tabindex="1" id="addAddress" accesskey="1">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">City</td>
      <td class="table_row"><span id="sprytextfield4">
        <input name="addCity" type="text" value="<?php echo $Address_row->getField('addCity'); ?>" tabindex="2" id="addCity" accesskey="2">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">State</td>
      <td class="table_row"><span id="sprytextfield5">
        <input name="addState" type="text" value="<?php echo $Address_row->getField('addState'); ?>" tabindex="3" id="addState" accesskey="3">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Zip</td>
      <td class="table_row"><span id="sprytextfield6">
      <input name="addZip" type="text" value="<?php echo $Address_row->getField('addZip'); ?>" tabindex="4" id="addZip" accesskey="4">
      <span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Country</td>
      <td class="table_row"><span id="sprytextfield7">
        <input name="addCountry" type="text" value="<?php echo $Address_row->getField('addCountry'); ?>" tabindex="5" id="addCountry" accesskey="5">
        </span></td>
    </tr>
  </table>
  
  <div class="btn_submit"><input type="button" value="Save" id="save_button" onClick="validateOnSubmit();"></div>
  
  </form>
<!-- end #mainContent --></div>  
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
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {maxChars:10});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
