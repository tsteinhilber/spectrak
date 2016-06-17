<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Printer_find = $ContactLogin->newFindCommand('sup-Printer');
$Printer_findCriterions = array('cmp_PK'=>fmsEscape($_REQUEST['p']),);
foreach($Printer_findCriterions as $key=>$value) {
    $Printer_find->AddFindCriterion($key,$value);
}

fmsSetPage($Printer_find,'Printer',1);

$Printer_result = $Printer_find->execute();

if(FileMaker::isError($Printer_result)) fmsTrapError($Printer_result,"error.php");

fmsSetLastPage($Printer_result,'Printer',1); 

$Printer_row = current($Printer_result->getRecords());

$Printer__calc_portal = fmsRelatedRecord($Printer_row, 'calc');
$Printer__Sup_portal = fmsRelatedRecord($Printer_row, 'Sup');
$Printer__SupJob_portal = fmsRelatedRecord($Printer_row, 'SupJob');
$Printer__SupJobCmpPRINTERCon_portal = fmsRelatedRecord($Printer_row, 'SupJobCmpPRINTERCon');
$Printer__SupJobCmpPRINTERSpe_portal = fmsRelatedRecord($Printer_row, 'SupJobCmpPRINTERSpe');
$Printer__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Printer_row, 'SupJobCmpPRINTERAdd');

 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php
$printerID = $Printer_row->getField('cmp_PK');
$companyName = $Printer_row->getField('cmpCompany');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Printer: <?php echo $Printer_row->getField('cmpCompany'); ?></title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->

<link rel="icon" type="image/gif" href="img/favicon.gif">

<!-- CSS LINKS -->
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script language="JavaScript">

var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';

$(document).ready(function(){
	
	$("#contacts tr, #addresses tr, #spec tr").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
	)
	toggleInfoPanel(infoReturned, infoPassFail);
	});
   
function specNew(){
	  document.spec_new.submit();
   }
	
function validateOnSubmit(){
	var myForm=document.getElementById("edit_printer");
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
    <h1>Printer: <?php echo $Printer_row->getField('cmpCompany'); ?></h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a onclick="linkOutAlert('index.php');">Home</a></li>
    <li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>');">Logout</a></li>
	<li><a onclick="linkOutAlert('supplier.php');">Update Company Contact Info</a></li>
    <li><a onclick="linkOutAlert('jobs.php');">Jobs</a></li>
  </ul>
  
  <!-- ADD NEW -->
  <h1>Add New</h1>
  <ul id="add_new" class="MenuBarVertical">
    <li><a href="printer_address_new.php?p=<?php echo $Printer_row->getField('cmp_PK'); ?>">Address</a></li>
	<li><a href="printer_contact_new.php?p=<?php echo $Printer_row->getField('cmp_PK'); ?>">Contact</a></li>
	<?php if($Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::con_PK')){ ?>
		<li><a onclick="specNew()">Specification</a></li>
	<? } ?>
  </ul>
  
  <!-- ACTIONS -->
  <h1>Actions</h1>
  <ul id="actions" class="MenuBarVertical">
    <li><a onclick="validateOnSubmit()">Save</a></li>
</ul>

<!-- end #sidebar1 --></div>
  
  <div id="mainContent">
  
  <!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>
   
   <!-- INSTRUCTIONS -->
    <span id=instructions><span class="callout">INSTRUCTIONS:</span> Choose an individual contact from the list below to edit contact information, or select the “Create New Contact” option from the menu on the left. If a contact is no longer with your company, please click on the name and choose <em>Make Contact Inactive.</em></span>
    
	<!-- PRINTER EDIT FORM -->
	<form action="printer_edit_response.php" method="post" name="edit_printer" id="edit_printer">
      <table class="table" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td class="table_header" colspan="2">General info</td>
        </tr>
        <tr>
          <td class="table_side_head">Website</td>
          <td class="table_row"><span id="sprytextfield2">
            <input name="cmpWebsite" type="text" value="<?php echo $Printer_row->getField('cmpWebsite'); ?>" id="cmpWebsite">
          </span></td>
        </tr>
        <input type="hidden" name="-recid" value="<?php echo $Printer_row->getRecordId(); ?>" />
      </table>
	  <input name="-recid" type="hidden" value="<?php echo $Printer_row->getRecordId(); ?>" id="-recid" />
    </form>
    
    <!-- CONTACTS -->
    <table id="contacts" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" width="20%">Contacts</td>
        <td class="table_header" width="17%">&nbsp;</td>
        <td class="table_header" width="17%">&nbsp;</td>
        <td class="table_header" width="17%">&nbsp;</td>
		<td class="table_header" width="20%">&nbsp;</td>
        <td class="table_header" align="RIGHT" width="60">
        
        <!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('printer_contact_new.php?p=<?php echo $Printer_row->getField('cmp_PK'); ?>')"></a></li>
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
      </tr>
      <tr class="table_sub_head">
      	<td>Name</td>
        <td width="17%">Title</td>
        <td width="17%">Phone</td>
        <td width="17%">Email</td>
        <td>Address</td>
		<td width="60">Status</td>
      </tr>
      
      <?php if(!$Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::con_PK')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="6">Currently there are no contacts for <?php echo $Printer_row->getField('cmpCompany'); ?>.</td>
      </tr>
      <? } ?>
      
      <?php
$Printer__SupJobCmpPRINTERCon_portal_rownum = 1;
foreach(fmsRelatedSet($Printer_row,'SupJobCmpPRINTERCon') as $Printer__SupJobCmpPRINTERCon_portal_row=>$Printer__SupJobCmpPRINTERCon_portal){
	
	$cmpName = $Printer_row->getField('cmpCompany');
	$cmp_PK = $Printer_row->getField('cmp_PK');
	$con_PK = $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::con_PK');
	$statusNum = $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conStatus');
	
	if($statusNum == 1){
			$status = "Active";
			$statusImg = "img/conActive.png";
			$title = "Make Inactive";
			$class1 = "table_row_borderR_arrow";
			$class2 = "table_row_borderR";
			$class3 = "table_row";
			$sendStatus = "0";
		} else {
			$status = "Inactive";
			$statusImg = "img/conInactive.png";
			$title = "Make Active";
			$class1 = "table_row_borderR_arrow_inactive";
			$class2 = "table_row_borderR_inactive";
			$class3 = "table_row_inactive";
			$sendStatus = "1";
		}
	
?>
        <tr>
          <td class="<?php echo $class1; ?>" width="20%" title="Edit: <?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conNameFull'); ?>" onClick="openURL('printer_contact.php?c=<?php echo $con_PK; ?>')"><?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conNameFull'); ?></td>
          <td class="<?php echo $class2; ?>" width="17%" onClick="openURL('printer_contact.php?c=<?php echo $con_PK; ?>')"><?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conTitle'); ?></td>
          <td class="<?php echo $class2; ?>" width="17%" onClick="openURL('printer_contact.php?c=<?php echo $con_PK; ?>')"><?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conPhoneFull'); ?></td>
          <td class="<?php echo $class2; ?>" width="17%" onClick="openURL('printer_contact.php?c=<?php echo $con_PK; ?>')"><?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::conEmail'); ?></td>
          <td class="<?php echo $class2; ?>" width="20%" onClick="openURL('printer_contact.php?c=<?php echo $con_PK; ?>')"><?php echo $Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERConAdd::addAddress'); ?></td>
		  <td class="<?php echo $class3; ?>" width="60" align="CENTER" valign="MIDDLE">
		  	<a onClick="change_conStatus('<?php echo $con_PK; ?>', '<?php echo $sendStatus; ?>')"><img name="conStatusImg" src="<?php echo $statusImg; ?>" width="" height="" alt="<?php echo $status; ?>" title="<?php echo $title; ?>"></a>
		  </td>
        </tr>
        <?php if($Printer__SupJobCmpPRINTERCon_portal_rownum == 0) break; else $Printer__SupJobCmpPRINTERCon_portal_rownum++;
}//portal_end ?>
    </table>
    
    <!-- ADDRESSES -->
    <table id="addresses" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Addresses
          
          <!-- ADD BTN -->
          <ul id="addBtn">
            <li><a onClick="openURL('printer_address_new.php?p=<?php echo $Printer_row->getField('cmp_PK'); ?>')"></a></li>
          </ul>
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
         </td>
      </tr>
      
      <?php if(!$Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::add_PK')){ ?>
      <tr>
      	<td class="table_row_instr">Currently there are no addresses for <?php echo $Printer_row->getField('cmpCompany'); ?>.</td>
      </tr>
      <? } ?>
      
      <?php
$Printer__SupJobCmpPRINTERAdd_portal_rownum = 1;
foreach(fmsRelatedSet($Printer_row,'SupJobCmpPRINTERAdd') as $Printer__SupJobCmpPRINTERAdd_portal_row=>$Printer__SupJobCmpPRINTERAdd_portal){ 

	$cmpName = $Printer_row->getField('cmpCompany');
	$cmp_PK = $Printer_row->getField('cmp_PK');
	$add_PK = $Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::add_PK');
?>
        <tr onClick="openURL('printer_address.php?a=<?php echo $add_PK; ?>')">
          <td class="table_row_borderR_arrow" title="Edit: <?php echo $Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addAddress'); ?>"><?php echo $Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addAddress'); ?> <?php echo $Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addCityStateZip'); ?> <?php echo $Printer__SupJobCmpPRINTERAdd_portal->getField('SupJobCmpPRINTERAdd::addCountry'); ?></td>
        </tr>
        <?php if($Printer__SupJobCmpPRINTERAdd_portal_rownum == 0) break; else $Printer__SupJobCmpPRINTERAdd_portal_rownum++;
}//portal_end ?>
    </table>
	
<!-- SPECS -->
<table id="spec" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td class="table_header">Specifications</td>
	<td class="table_header" align="RIGHT">
	
	<!-- CHECK FOR CONTACTS -->
	<?php if($Printer__SupJobCmpPRINTERCon_portal->getField('SupJobCmpPRINTERCon::con_PK')){ ?>
	
	<!-- ADD BTN -->
	<ul id="addBtn">
		<li onclick="specNew()"><a></a></li>
	</ul>
	<script>
			$("ul:last").qtip({ 
				show: 'mouseover',
				hide: 'mouseout',
				content: 'Create New Specification',
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
	
	<? } ?>
		
	</td>
  </tr>
  
  <?php if(!$Printer__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpe::_PrimeSpeIDX')){ ?>
  <tr>
	<td class="table_row_instr" colspan="2">Currently there are no specs for <?php echo $Printer_row->getField('cmpCompany'); ?>. Contacts must be created before Specifications can be created.</td>
  </tr>
  <? } ?>
  
  <?php
$Printer__SupJobCmpPRINTERSpe_portal_rownum = 1;
foreach(fmsRelatedSet($Printer_row,'SupJobCmpPRINTERSpe') as $Printer__SupJobCmpPRINTERSpe_portal_row=>$Printer__SupJobCmpPRINTERSpe_portal){ 
?>
	<tr 	onmouseover="ChangeColor(this, true);" 
			onmouseout="ChangeColor(this, false);" 
			onclick="openURL('spec.php?s=<?php echo $Printer__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpe::_PrimeSpeIDX');?>')
			">
	  
	  <td class="table_row_borderR_arrow" width="50%"><?php echo $Printer__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpe::speName'); ?></td>
	  <td class="table_row"><?php echo $Printer__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpeCon::conNameFull'); ?></td>
	</tr>
	<?php if($Printer__SupJobCmpPRINTERSpe_portal_rownum == 0) break; else $Printer__SupJobCmpPRINTERSpe_portal_rownum++;
}//portal_end ?>
</table>
    
    <!-- SPEC_NEW FORM -->
    <form action="spec_new.php" method="post" enctype="application/x-www-form-urlencoded" name="spec_new" id="spec_new">
      <input name="com" type="hidden" value="<?php echo $Printer_row->getField('cmpCompany'); ?>" id="com" />
      <input name="p" type="hidden" value="<?php echo $Printer_row->getField('cmp_PK'); ?>" id="p">
    </form>
    
	<!--CONTACT ACTIVE/INACTIVE FORM-->
	<form action="contact_activeInactive.php" method="post" id="contact_activeInactive" name="contact_activeInactive">
		<input name="con_PK" type="hidden" id="con_PK" />
		<input name="conStatus" type="hidden" id="conStatus" />
	</form>
	
<!-- end #mainContent -->
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"], isRequired:false});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
