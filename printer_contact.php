<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Contact_find = $ContactLogin->newFindCommand('sup-Contact');
$Contact_findCriterions = array('con_PK'=>$_REQUEST['c'],);
foreach($Contact_findCriterions as $key=>$value) {
    $Contact_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Contact_find,'Contact',1); 

$Supplier_result = $Supplier_find->execute(); 

$Contact_result = $Contact_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($Contact_result)) fmsTrapError($Contact_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($Contact_result,'Contact',1); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

$Contact_row = current($Contact_result->getRecords());

$Contact__calc_portal = fmsRelatedRecord($Contact_row, 'calc');
$Contact__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTER');
$Contact__Sup_portal = fmsRelatedRecord($Contact_row, 'Sup');
$Contact__SupJobCmpPRINTERConAdd_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTERConAdd');
$Contact__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTERAdd');
 
$printer = $_SERVER['HTTP_REFERER']; 
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | <?php echo $Contact_row->getField('SupJobCmpPRINTER::cmpCompany'); ?> Contact: <?php echo $Contact_row->getField('conNameF'); ?> <?php echo $Contact_row->getField('conNameL'); ?></title>
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
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script type="text/javascript">
	
	function validateOnSubmit(){
		var myForm = document.getElementById("edit_contact");
		var SS = Spry.Widget.Form.validate(myForm);
		if(SS == true){
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues saving this contact", "Please enter valid data into the fields highlighted in red");
			highlightErrors();
		}
	}
	
	function makeInactive(){
		var myForm = document.getElementById("edit_contact");
		var SS = Spry.Widget.Form.validate(myForm);
		$("#conStatus").val("");
		if(SS == true){
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues saving this contact", "Please enter valid data into the fields highlighted in red");
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
    <h1><?php echo $Contact_row->getField('SupJobCmpPRINTER::cmpCompany'); ?> Contact</h1>
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
  <li><a onclick="validateOnSubmit();">Save</a></li>
  <li><a onclick="makeInactive();">Make Contact Inactive</a></li>
  <li><a onclick="linkOutAlert('<? echo $printer; ?>');">Cancel</a></li>
</ul>
<!-- end #sidebar1 --></div>
  
  <div id="mainContent">

	<!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>

    <p><span class="callout">INSTRUCTIONS:</span> If a contact is no longer with your company, please click on the name and choose <em>Make Contact Inactive.</em></p>
<form id="edit_contact" name="edit_contact" method="post" action="contact_edit_response.php">
  <input name="-recid" type="hidden" value="<?php echo $Contact_row->getRecordId(); ?>" id="-recid">
        <input name="x" type="hidden" value="1" id="x">
        <input name="cmp_PK" type="hidden" value="<?php echo $Contact_row->getField('con_FK_cmp'); ?>" id="cmp_PK">
        <input name="conStatus" type="hidden" value="1" id="conStatus">

  <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="table_side_head_req">First Name</td>
      <td class="table_row"><span id="sprytextfield3">
        <input name="conNameF" type="text" value="<?php echo $Contact_row->getField('conNameF'); ?>" tabindex="1" id="conNameF" accesskey="1">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Last Name</td>
      <td class="table_row"><span id="sprytextfield4">
        <input name="conNameL" type="text" value="<?php echo $Contact_row->getField('conNameL'); ?>" tabindex="2" id="conNameL" accesskey="2">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Title</td>
      <td class="table_row"><span id="sprytextfield5">
        <input name="conTitle" type="text" value="<?php echo $Contact_row->getField('conTitle'); ?>" tabindex="3" id="conTitle" accesskey="3">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Email</td>
      <td class="table_row"><span id="sprytextfield6">
      <input name="conEmail" type="text" value="<?php echo $Contact_row->getField('conEmail'); ?>" tabindex="4" id="conEmail" accesskey="4">
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Phone Direct</td>
      <td class="table_row"><span id="sprytextfield7">
        <input name="conPhoneDirect" type="text" value="<?php echo $Contact_row->getField('conPhoneDirect'); ?>" tabindex="5" id="conPhoneDirect" accesskey="5">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head">Phone Ext.</td>
      <td class="table_row"><span id="sprytextfield8">
        <input name="conPhoneExt" type="text" value="<?php echo $Contact_row->getField('conPhoneExt'); ?>" tabindex="6" id="conPhoneExt" accesskey="6">
        </span></td>
    </tr>
    <tr>
      <td class="table_side_head_req">Location</td>
      <td class="table_row">
		<span class="">
      <select name="con_FK_add" id="con_FK_add">
          <?php
			$Contact__SupJobCmpPRINTERAdd_portal_rownum = 1;
			foreach(fmsRelatedSet($Contact_row,'SupJobCmpPRINTERAdd') as $Contact__SupJobCmpPRINTERAdd_portal_row=>$Contact__SupJobCmpPRINTERAdd_portal){ 
			
				$add_PK = $Contact__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::add_PK');
				$con_FK_add = $Contact_row->getField('con_FK_add');
				
				if($add_PK == $con_FK_add){
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
			
			?>
          <option value="<?php echo $Contact__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::add_PK'); ?>" <?php echo $selected; ?>>
		  	<?php echo $Contact__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addAddress'); ?> <?php echo $Contact__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addCityStateZip'); ?>
          </option>
          
          <?php if($Contact__SupJobCmpPRINTERAdd_portal_rownum == 0) break; else $Contact__SupJobCmpPRINTERAdd_portal_rownum++;
}//portal_end ?>
          </select>
		</span>
      </td>
    </tr>
  </table>
  
  <div class="btn_submit">
	<span class="">
		<input type="button" id="save_button" value="Save" onclick="validateOnSubmit()" />
	    <input type="button" id="btn_make_inactive" value="Make Contact Inactive" onclick="makeInactive();">
	</span>
  </div>
  
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
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "email");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
