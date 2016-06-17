<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-Supplier');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

$Supplier_result = $Supplier_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupContact_portal = fmsRelatedRecord($Supplier_row, 'SupContact');
$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
$Supplier__SupDie_portal = fmsRelatedRecord($Supplier_row, 'SupDie');
$Supplier__SupImg_portal = fmsRelatedRecord($Supplier_row, 'SupImg');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | <?php echo $Supplier_row->getField('cmpCompany'); ?></title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->
<!--[if lt IE 7]>
<style type='text/css'>
	#simplemodal-container a.modalCloseImg {
		background:none;
		right:-14px;
		width:22px;
		height:26px;
		filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(
			src='img/x.png', sizingMethod='scale'
		);
	}
</style>
<![endif]-->

<link rel="icon" type="image/gif" href="img/favicon.gif">
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script type="text/javascript">

var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';

$(document).ready(function(){
	
	toggleInfoPanel(infoReturned, infoPassFail);
	
	$("#contacts tr, #addresses tr").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
	)
	
});

function validateOnSubmit(){
	var myForm=document.getElementById("edit_supplier");
	var SS= Spry.Widget.Form.validate(myForm);
	if(SS==true){
	myForm.submit();
	}
}

function change_conStatus(pk, status){
	$("#con_PK").val(pk);
	$("#conStatus").val(status);
	$("#contact_activeInactive").submit();
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
    <h1><?php echo $Supplier_row->getField('cmpCompany'); ?></h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a onclick="linkOutAlert('index.php');">Home</a></li>
    <li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>');">Logout</a></li>
    <li><a onclick="linkOutAlert('jobs.php');">Jobs</a></li>
  </ul>

<h1>Add New</h1>
<ul id="add_new" class="MenuBarVertical">
	<li><a href="supplier_contact_new.php">Contact</a></li>
	<li><a href="supplier_address_new.php">Address</a></li>
</ul>
  
<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">
	<li><a onclick="validateOnSubmit()">Save</a></li>
</ul>

  <!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">

<!-- INFO PANEL --> 
<div id="infoPaneL" class=""></div>
  
  <!-- GENERAL INFO -->
    <form id="edit_supplier" name="edit_supplier" method="post" action="supplier_edit_response.php">
    <p><span class="callout">INSTRUCTIONS</span>:</p>
    <ul>
      <li>To add a new Contact or Address, choose Add New Contact or Add New Address from the side menu or click on the + buttons.</li>
      <li>To edit existing information, click on the contact name.</li>
      <li>If a contact is no longer with your company, click on the button in the Status column to change their status to Inactive.</li>
    </ul>
    <table id="general_info" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Contact info</td>
      </tr>
      <tr>
        <td class="table_side_head">Website</td>
        <td class="table_row"><span id="sprytextfield2">
          <input name="cmpWebsite" type="text" value="<?php echo $Supplier_row->getField('cmpWebsite'); ?>" id="cmpWebsite">
  </span></td>
      </tr>
    </table>
    <input name="-recid" type="hidden" value="<?php echo $Supplier_row->getRecordId(); ?>" id="-recid" />
    </form>
    
    <!-- CONTACTS -->
    <table id="contacts" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Contacts</td>
        <td class="table_header" width="17%">&nbsp;</td>
        <td class="table_header" width="17%">&nbsp;</td>
        <td class="table_header" width="17%">&nbsp;</td>
		<td class="table_header">&nbsp;</td>
        <td class="table_header" align="RIGHT" width="60">
        <!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('supplier_contact_new.php')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({ 
					show: 'mouseover',
					hide: 'mouseout',
					content: 'Create New Contact',
				   	style: { 
					  	name: 'mystyle' // Inherit from preset style
				   	},
					position: {
					  corner: {
						 target: 'topLeft',
						 tooltip: 'bottomRight'
					  }
				   }
				})
            </script>
        </td>
      </tr>

      <tr class="table_sub_head">
        <td width="20%">Name</td>
        <td width="17%">Title</td>
        <td width="17%">Phone</td>
        <td width="17%">Email</td>
        <td>Address</td>
		<td width="60">Status</td>
      </tr>

      <?php
$Supplier__SupContact_portal_rownum = 1;
foreach(fmsRelatedSet($Supplier_row,'SupContact') as $Supplier__SupContact_portal_row=>$Supplier__SupContact_portal){ 

		$cmpName = $Supplier_row->getField('cmpCompany');
		$con_PK = $Supplier__SupContact_portal->getField('SupContact::con_PK');
		$statusNum = $Supplier__SupContact_portal->getField('SupContact::conStatus');
		
		if($statusNum == 1){
			$status = "Active";
			$statusImg = "img/conActive.png";
			$title = "Make Inactive";
			$class1 = "table_row_borderR_arrow";
			$class2 = "table_row_borderR";
			$class3 = "table_row";
			$sendStatus = "0";
			$urlString = "'supplier_contact.php?c=" . $con_PK . "'";
			$clickable = "onClick=\"openURL($urlString)\"";
			$iconMessage = "Edit: " . $Supplier__SupContact_portal->getField('SupContact::conNameFull');
		} else {
			$status = "Inactive";
			$statusImg = "img/conInactive.png";
			$title = "Make Active";
			$class1 = "table_row_borderR_arrow_inactive";
			$class2 = "table_row_borderR_inactive";
			$class3 = "table_row_inactive";
			$sendStatus = "1";
			$clickable = "";
			$iconMessage = "Activate to edit: " . $Supplier__SupContact_portal->getField('SupContact::conNameFull');
		}
?>
        
      <tr>
        <td class="<?php echo $class1; ?>" width="20%" title="<?php echo $iconMessage ?>" <?php echo $clickable; ?>><?php echo $Supplier__SupContact_portal->getField('SupContact::conNameFull'); ?></td>
        <td class="<?php echo $class2; ?>" width="17%" <?php echo $clickable; ?>><?php echo $Supplier__SupContact_portal->getField('SupContact::conTitle'); ?></td>
        <td class="<?php echo $class2; ?>" width="17%" <?php echo $clickable; ?>><?php echo $Supplier__SupContact_portal->getField('SupContact::conPhoneFull'); ?></td>
        <td class="<?php echo $class2; ?>" width="17%" <?php echo $clickable; ?>><?php echo $Supplier__SupContact_portal->getField('SupContact::conEmail'); ?></td>
        <td class="<?php echo $class2; ?>" width="20%" <?php echo $clickable; ?>><?php echo $Supplier__SupContact_portal->getField('SupConAddress::addAddress'); ?></td>
		<td class="<?php echo $class3; ?>" width="60" align="CENTER" valign="MIDDLE">
			<a onClick="change_conStatus('<?php echo $con_PK; ?>', '<?php echo $sendStatus; ?>')"><img name="conStatusImg" src="<?php echo $statusImg; ?>" width="" height="" alt="<?php echo $status; ?>" title="<?php echo $title; ?>"></a>
		</td>
      </tr>
      <?php if($Supplier__SupContact_portal_rownum == 0) break; else $Supplier__SupContact_portal_rownum++;
}//portal_end ?>
    </table>
    
    <!-- ADDRESSES -->
    <table id="addresses" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Addresses
          <!-- ADD BTN -->
          <ul id="addBtn">
            <li><a onClick="openURL('supplier_address_new.php')"></a></li>
          </ul>        
        </td>
        <script>
				$("ul:last").qtip({ 
					show: 'mouseover',
					hide: 'mouseout',
					content: 'Create New Address',
				   	style: { 
					  	name: 'mystyle' // Inherit from preset style
				   	},
					position: {
					  corner: {
						 target: 'topLeft',
						 tooltip: 'bottomRight'
					  }
				   }
				})
            </script>
      </tr>
      <?php
$Supplier__SupAddress_portal_rownum = 1;
foreach(fmsRelatedSet($Supplier_row,'SupAddress') as $Supplier__SupAddress_portal_row=>$Supplier__SupAddress_portal){ 

	$cmpName = $Supplier_row->getField('cmpCompany');
	$add_PK = $Supplier__SupAddress_portal->getField('SupAddress::add_PK');

?>
      <tr onClick="openURL('supplier_address.php?a=<?php echo $add_PK; ?>')">
        <td class="table_row_borderR_arrow" title="Edit: <?php echo $Supplier__SupAddress_portal->getField('SupAddress::addAddress'); ?>"><?php echo $Supplier__SupAddress_portal->getField('SupAddress::addAddress'); ?> <?php echo $Supplier__SupAddress_portal->getField('SupAddress::addCityStateZip'); ?> <?php echo $Supplier__SupAddress_portal->getField('SupAddress::addCountry'); ?></td>
        </tr>
      <?php if($Supplier__SupAddress_portal_rownum == 0) break; else $Supplier__SupAddress_portal_rownum++;
}//portal_end ?>
    </table>
	
	<!--ACTIVE/INACTIVE FORM-->
	<form action="contact_activeInactive.php" method="post" id="contact_activeInactive" name="contact_activeInactive">
		<input name="con_PK" type="hidden" id="con_PK" />
		<input name="conStatus" type="hidden" id="conStatus" />
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

<!-- SPRY -->
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {isRequired:false});
//-->
</script>
</body>
</html>
