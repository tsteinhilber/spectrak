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

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!-- FIND CONTACTS -->
<?php
$Contacts_find = $ContactLogin->newFindCommand('sup-Contact');
$Contacts_findCriterions = array('con_FK_cmp'=>$_REQUEST['p'],);
foreach($Contacts_findCriterions as $key=>$value) {
    $Contacts_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contacts_find,'Contacts',100);

$Contacts_find->addSortRule('conNameL', 1, FILEMAKER_SORT_ASCEND);

$Contacts_result = $Contacts_find->execute();


// if(FileMaker::isError($Contacts_result)) fmsTrapError($Contacts_result,"error.php");
if(FileMaker::isError($Contacts_result)) $contact_error = 1 ;

fmsSetLastPage($Contacts_result,'Contacts',100);

if (!$contact_error){
	$Contacts_row = current($Contacts_result->getRecords());
	$Contacts__calc_portal = fmsRelatedRecord($Contacts_row, 'calc');
	$Contacts__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTER');
	$Contacts__SupJobCmpPRINTERConAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERConAdd');
	$Contacts__Sup_portal = fmsRelatedRecord($Contacts_row, 'Sup');
	$Contacts__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERAdd');
}

?>

<!-- FIND ADDRESSES -->
<?php
$Addresses_find = $ContactLogin->newFindCommand('sup-Address');
$Addresses_findCriterions = array('add_FK_cmp'=>$_REQUEST['p'],);
foreach($Addresses_findCriterions as $key=>$value) {
    $Addresses_find->AddFindCriterion($key,$value);
}

fmsSetPage($Addresses_find,'Addresses',20);

$Addresses_result = $Addresses_find->execute();

// if(FileMaker::isError($Addresses_result)) fmsTrapError($Addresses_result,"error.php");
if(FileMaker::isError($Contacts_result)) $address_error = 1 ;

fmsSetLastPage($Addresses_result,'Addresses',20);

if (!$address_error){
	$Addresses_row = current($Addresses_result->getRecords());
}

?>

<?php
$printerID = $_REQUEST['p'];


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
	<title>SpecTrak | <?php echo $_REQUEST['com']; ?>: New Specification</title>
	<link href="CSS/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
	<style type="text/css">
	/* place css fixes for all versions of IE in this conditional comment */
	.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
	.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
	/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
	</style>
	<![endif]-->
	<link rel="icon" type="image/gif" href="img/favicon.gif">
	<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
	<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />

	<!--JAVASCRIPTS-->
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="js/application.js"></script>
	<script type="text/javascript" src="js/spec_new.js"></script>


	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
	<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
	<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>

<script language="JavaScript">
$(document).ready(function(){
	var contactID = '<? echo $_REQUEST['newContactID']; ?>';
	var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
	var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
	var colorsNum = "";
	var printProcess = "";
	var spotColorNum = "";
	var substrate = "";
	var substrateOther = "";

	// Hide New Contact Table
	$("#new_contact").hide();

	$("#attachment_table").remove();

	// Hide Backgrounder
	$("#backgrounder").hide();

	// Select newContactID
	if(contactID){
		$("#Spe_ConIDX").val(contactID);
		toggleInfoPanel(infoReturned, infoPassFail);
	}

	// Hide Substrate Other
	$("#speSubstrateOther").hide();

	// Toggle speSubstrateOther
	$("#speSubstrate").change(function(){
		var speSubstrate = $("#speSubstrate").val();
		if(speSubstrate == "Other"){
			$("#speSubstrateOther").show();
		} else {
			$("#speSubstrateOther").hide();
		}
	});

	// Initial Toggle numberOfColors
	var colorsNum = $("#speMaxNumColors").val();
	if(colorsNum == "4-color process plus" || colorsNum == "Spot colors ONLY"){
		$("#numberOfColors").show();
	} else {
		$("#numberOfColors").hide();
	}

	// Toggle numberOfColors
	$("#speMaxNumColors").change(function(){
		colorsNum = $("#speMaxNumColors").val();
		if(colorsNum == "4-color process plus" || colorsNum == "Spot colors ONLY"){
			$("#numberOfColors").show();
		} else {
			$("#numberOfColors").hide();
		}
	});

	// New Contact Cancel Button
	$("input[name='new_contact_cancel']").click(function(){
		$("#Spe_ConIDX").val("");
		closeNewContact();
	});

	// New Contact Save Button
	$("#new_contact_save").click(function(){
		//alert("Validate & Submit New Contact");
		validateContact();
	});

	// New Contact Select Change
	$("#Spe_ConIDX").change(function(){
		s = $("#Spe_ConIDX option:selected").val();
		if(s == "new"){
			openNewContact();
		} else {
			closeNewContact();
		}
	});

});

// OPEN NEW CONTACT
function openNewContact(){
	$("#backgrounder").fadeIn("slow");
	$("#new_contact").fadeIn();
	$("#conNameF").focus();
}

// CLOSE NEW CONTACT
function closeNewContact(){
	$("#contact_table input, #contact_table select").val("");
	$("#backgrounder").fadeOut("fast");
	$("#new_contact").hide();
	resetAllInputCss();
	$("#infoPanel").html("");
}

// CANCEL NEW CONTACT
function cancelNewContact(){
	$("#contact_table select").val("");
}

// RESET ALL INPUT CSS
function resetAllInputCss() {
	$("input, textarea, select, .table_row").css("background-color", "#fff");
	$(".table_side_head_req, .table_side_head").css("background-color", "#efefef");
}

// VALIDATE CONTACT
function validateContact(){
	contactID = $("#Spe_ConIDX").val();
	firstName = $("#conNameF");
	lastName = $("#conNameL");
	phoneDirect = $("#conPhoneDirect");
	title = $("#conTitle");
	address = $("#con_FK_add");
	email = $("#conEmail");
	var contactForm = document.getElementById("new_contact_form");
	var contact_validation = true;

	if(contactID == "new"){
		if(!firstName.val()){
			contact_validation = false;
			firstName.parent().parent().children('td').css("background-color","#FF99A8");
		}
		if(!lastName.val()){
			contact_validation = false;
			lastName.parent().parent().children('td').css("background-color","#FF99A8");
		}
		if(!title.val()){
			contact_validation = false;
			title.parent().parent().children('td').css("background-color","#FF99A8");
		}
		if(!email.val()){
			contact_validation = false;
			email.parent().parent().children('td').css("background-color","#FF99A8");
		}
		if(!phoneDirect.val()){
			contact_validation = false;
			phoneDirect.parent().parent().children('td').css("background-color","#FF99A8");
		}
		if(!address.val()){
			contact_validation = false;
			address.parent().parent().children('td').css("background-color","#FF99A8");
		}
	}

	if(contact_validation == true){
		contactForm.submit();
		//alert("validateContact TRUE");
	} else {
		flashNotice("NOTICE: There were issues creating this contact", "Please enter valid data into the fields highlighted in red");
	}
}

// VALIDATE SPEC
function validateSpec(){
	resetAllInputCss();
	colorsNum = $("#speMaxNumColors").val();
	printProcess = $("#spePrintProcess").val();
	spotColorNum = $("#speMaxNumColorsSpotNum").val();
	substrate = $("#speSubstrate").val();
	substrateOther = $("#speSubstrateOther").val();
	spec_validation = true;

	// colorsNum validation
	if(!colorsNum) {
		$("#speMaxNumColors").parent().parent().parent().children('td').css("background-color","#FF99A8");
		spec_validation = false;
	}

	// colorsNum + spotColorNum validation
	if(colorsNum == "4-color process plus" && spotColorNum == "" || colorsNum == "Spot colors ONLY" && spotColorNum == ""){
		$("#speMaxNumColorsSpotNum").css("background-color","#FF99A8");
		spec_validation = false;
	}

	// printProcess validation
	if(!printProcess) {
		$("#spePrintProcess").parent().parent().parent().children('td').css("background-color","#FF99A8");
		spec_validation = false;
	}

	// substrate validation
	if(!substrate) {
		$("#speSubstrate").parent().parent().parent().children('td').css("background-color","#FF99A8");
		spec_validation = false;
	}

	// substrate check
	if(substrate == "Other" && substrateOther == ""){
		$("#speSubstrateOther").css("background-color","#FF99A8");
		spec_validation = false;
	}

	if(substrate != "Other"){
		$("#speSubstrateOther").val("");
	}
}

// VALIDATE SAVE
function validateSave(){
	var myForm = document.getElementById("new_spec");
	validateSpec();
	if(spec_validation){
		if($('#file').val()){
			$('#upload').val('TRUE');
		} else {
			$('#upload').val('FALSE');
		}
		myForm.submit();
	} else {
		flashNotice("NOTICE: There were issues creating this specification", "Please enter valid data into the fields highlighted in red");
	}
}

// VALIDATE SUBMIT
function validateSubmit(){
	var myForm = document.getElementById("new_spec");
	var SS = Spry.Widget.Form.validate(myForm);
	validateSpec();
	if(SS == true && spec_validation == true){
		$("#statusComplete").val("1");
		if($('#file').val()){
			$('#upload').val('TRUE');
		} else {
			$('#upload').val('FALSE');
		}
		myForm.submit();
	} else {
		flashNotice("NOTICE: There were issues creating this specification", "Please enter valid data into the fields highlighted in red");
		highlightErrors();
	}
}

</script>

</head>

<body class="twoColHybLtHdr" id='spec_new'>

<div id='backgrounder'></div>

<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a onclick="linkOutAlert('index.php');"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1><?php echo $_REQUEST['com']; ?>: New Printer specification</h1>
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
  <ul id="spec_tasks" class="MenuBarVertical">
	<li><a onclick="validateSave()">Save</a></li>
	<li><a onclick="validateSubmit()">Complete</a></li>
    <li><a href="<?php echo $oURL; ?>">Cancel</a></li>
</ul>

</div><!-- end #sidebar1 -->

<!-- MAIN CONTENT -->
  <div id="mainContent">

	<!-- INFO PANEL -->
	<div id="infoPanel" class=""></div>

	<!-- INSTRUCTIONS -->
    <span id='instructions'><span class="callout">INSTRUCTIONS:</span> Please enter item information in the fields below. <em>Print Process, Substrate and Colors Allowed</em> fields are required to Save. Select <em>Save</em> to if you would like to come back and complete the form at a later time. Select <em>Complete</em> if the information is complete.</span>

    <!-- NEW CONTACT FORM -->
	<div id="new_contact">
	<form id="new_contact_form" name="new_contact_form" method="post" action="contact_new_response.php">

		<table id="contact_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td colspan="2" class="table_header">NEW CONTACT</td>
        </tr>
		<tr>
		  <td class="table_side_head_req">First Name</td>
		  <td class="table_row">
			<input name="conNameF" type="text" tabindex="1" id="conNameF">
		  </td>
		</tr>
		<tr>
		  <td class="table_side_head_req">Last Name</td>
		  <td class="table_row">
			<input name="conNameL" type="text" tabindex="2" id="conNameL">
		  </td>
		</tr>
		<tr>
		  <td class="table_side_head_req">Title</td>
		  <td class="table_row">
			<input name="conTitle" type="text" tabindex="3" id="conTitle">
		  </td>
		</tr>
		<tr>
		  <td class="table_side_head_req">Email</td>
		  <td class="table_row">
			<input name="conEmail" type="text" tabindex="4" id="conEmail">
		  </td>
		</tr>
		<tr>
		  <td class="table_side_head_req">Phone Direct</td>
		  <td class="table_row">
			<input name="conPhoneDirect" type="text" tabindex="5" id="conPhoneDirect">
		  </td>
		</tr>
		<tr>
		  <td class="table_side_head">Phone Ext.</td>
		  <td class="table_row">
			<input name="conPhoneExt" type="text" tabindex="6" id="conPhoneExt" accesskey="6">
		  </td>
		</tr>
		<tr id="address_old">
		  <td class="table_side_head_req">Location</td>

		</tr>
		</table>
		<input type="hidden" name="oURL" value="<?php echo $oURL; ?>" id="oURL">
		<input type="hidden" name="specID" value="new" id="specID">
		<input type="hidden" name="cmp_PK" value="<?php echo $_REQUEST['p']; ?>" id="cmp_PK">
		<input type="button" id="new_contact_save" value="Save New Contact" class='grayButton' />
		<input type="button" name="new_contact_cancel" value="Cancel" class='grayButton' />
	</form>
	</div>

	<!-- PRINTER SPECIFICATION FORM -->
	<form action="spec_new_response.php" method="post" enctype="multipart/form-data" name="new_spec" id="new_spec">

	  <table class="table" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" class="table_header">PRINTER SPECIFICATIONS</td>
        </tr>
		<tr>
          <td class="table_side_head_req">Printer Contact Assignment</td>
		  <td class="table_row">
			<?php if(!$contact_error): ?>
		  	<span id="spryselect8">
			<select name="Spe_ConIDX" id="Spe_ConIDX">
			  <option value="">Please select...</option>
			  <option value="new">Add New Contact</option>
			  <option disabled="disabled">—————————————</option>
			  <?php foreach($Contacts_result->getRecords() as $Contacts_row){ ?>
			  <option value="<?php echo $Contacts_row->getField('con_PK'); ?>" <?php echo $selected ?> ><?php echo $Contacts_row->getField('conNameF'); ?> <?php echo $Contacts_row->getField('conNameL'); ?></option>
			  <?php } ?>
        	</select>
        	</span>
			<?php else: ?>
				<p>No Contacts available</p>
			<?php endif ?>
		  </td>
        </tr>
		<tr>
          <td colspan="2" class="table_divider">GENERAL SPECIFICATIONS</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Print Process</td>
          <td class="table_row"><span id="spryselect1">
            <select name="spePrintProcess" id="spePrintProcess">
              <option value="">Please choose...</option>
              <option value="Offset Lithography">Offset Lithography</option>
              <option value="Flexography">Flexography</option>
			  <option value="Dry Offset">Dry Offset</option>
              <option value="Gravure">Gravure</option>
			  <option value="Flexo Heat Transfer">Flexo Heat Transfer</option>
			  <option value="Digital Printing">Digital Printing</option>
			  <option value="Other">Other</option>
            </select>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Substrate</td>
          <td class="table_row"><span id="spryselect2">
            <select name="speSubstrate" id="speSubstrate">
              <option value="">Please choose...</option>
              <option value="Paper">Paper</option>
              <option value="Board">Board</option>
              <option value="Clear Poly">Clear Poly</option>
              <option value="White Poly">White Poly</option>
              <option value="Plastic">Plastic</option>
              <option value="Foil">Foil</option>
			  <option value="Heat Transfer Stock (wax coated)">Heat Transfer Stock (wax coated)</option>
			  <option value="Other">Other</option>
            </select>
			<input name="speSubstrateOther" type="text" id="speSubstrateOther" class="input_50perc">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Colors Allowed</td>
          <td class="table_row"><span id="spryselect3">
          <select name="speMaxNumColors" id="speMaxNumColors">
            	<option value="">Please choose...</option>
                <option value="4-color process ONLY">4-color process ONLY</option>
                <option value="6-color process (Hexachrome, Hi-Colour, Hi-Fi)">6-color process (Hexachrome, Hi-Colour, Hi-Fi)</option>
                <option value="Spot colors ONLY">Spot colors ONLY</option>
                <option value="4-color process plus">4-color process plus</option>
				<option value="7-color process (Opaltone)">7-color process (Opaltone)</option>
            </select>
          </span>
		  <span id="numberOfColors">
          <span id="sprytextfield1">
            <input name="speMaxNumColorsSpotNum" type="text" id="speMaxNumColorsSpotNum" class="input_small">
</span>spots (one number)</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Ink Sequence</td>
          <td class="table_row"><span id="sprytextfield2">
            <input name="speInkSequence" type="text" id="speInkSequence">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Are items printed on Surface or Inside?</td>
          <td class="table_row"><span id="spryradio1">
          <label>
            <input type="radio" name="spePrintSurfaceOrInside" value="Surface" id="spePrintSurfaceOrInside_2">
            Surface</label>
          <label>
            <input type="radio" name="spePrintSurfaceOrInside" value="Inside" id="spePrintSurfaceOrInside_3">
            Inside</label>
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is there a Varnish or Aqueous Coating on this item?</td>
          <td class="table_row"><span id="spryradio2">
            <label>
              <input type="radio" name="speCoating" value="Yes" id="speCoating_0">
              Yes</label>
            <label>
              <input type="radio" name="speCoating" value="No" id="speCoating_1">
              No</label>
            </span>

          </td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is white ink required?</td>
          <td class="table_row"><span id="spryradio3">
            <label>
              <input type="radio" name="speWhiteInk" value="Yes" id="speWhiteInk_0">
              Yes</label>
            <label>
              <input type="radio" name="speWhiteInk" value="No" id="speWhiteInk_1">
              No</label>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is a screen of a spot considered an extra station on press?</td>
          <td class="table_row"><span id="spryradio4">
            <label>
              <input type="radio" name="speExtraStation" value="Yes" id="speExtraStation_0">
              Yes</label>
            <label>
              <input type="radio" name="speExtraStation" value="No" id="speExtraStation_1">
              No</label>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Are gradations feasible with this printing method?</td>
          <td class="table_row"><span id="spryradio5">
            <label>
              <input type="radio" name="speGradations" value="Yes" id="speGradations_0">
              Yes</label>
            <label>
              <input type="radio" name="speGradations" value="No" id="speGradations_1">
              No</label>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is there a minimum dot required?</td>
          <td class="table_row"><span id="spryradio6">
            <label>
              <input type="radio" name="speMinDot" value="Yes" id="speMinDot_0">
              Yes</label>
            <label>
              <input type="radio" name="speMinDot" value="No" id="speMinDot_1">
              No</label>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">If yes, what is the minimum dot? (%)</td>
          <td class="table_row"><span id="sprytextfield3">
            <input name="speMinDotPerc" type="text" id="speMinDotPerc">
          </span></td>
        </tr>
		<tr>
          <td class="table_side_head_req">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle document raster effects?</td>
          <td class="table_row"><span id="spryradio12">
            <label>
              <input type="radio" name="speDocumentRasterEffects" value="Yes" id="speDocumentRasterEffects">
              Yes</label>
            <label>
              <input type="radio" name="speDocumentRasterEffects" value="No" id="speDocumentRasterEffects">
              No</label>
            </span>

          </td>
        </tr>
		<tr>
          <td class="table_side_head_req">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle Adobe Illustrator transparency effects?</td>
          <td class="table_row"><span id="spryradio11">
            <label>
              <input type="radio" name="speAbobeIllustratorTransparencyEffects" value="Yes" id="speAbobeIllustratorTransparencyEffects_0">
              Yes</label>
            <label>
              <input type="radio" name="speAbobeIllustratorTransparencyEffects" value="No" id="speAbobeIllustratorTransparencyEffects_1">
              No</label>
            </span>

          </td>
        </tr>
        <tr class="table_sub_head">
          <td class="table_divider" colspan="2">Print Limitations:</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Rule Weight: Positive (pt)</td>
          <td class="table_row"><span id="sprytextfield4">
            <input name="speMinRuleWeightPositive" type="text" id="speMinRuleWeightPositive">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Rule Weight: Negative (pt)</td>
          <td class="table_row"><span id="sprytextfield5">
            <input name="speMinRuleWeightNegative" type="text" id="speMinRuleWeightNegative">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Type Size: Positive Copy (pt)</td>
          <td class="table_row"><span id="sprytextfield6">
            <input name="speMinTypeSizePositive" type="text" id="speMinTypeSizePositive">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Type Size: Reverse Copy (pt)</td>
          <td class="table_row"><span id="sprytextfield7">
            <input name="speMinTypeSizeReverse" type="text" id="speMinTypeSizeReverse">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Tolerance: Copy clearance from trims &amp; folds</td>
          <td class="table_row"><span id="sprytextfield8">
            <input name="speTolerance" type="text" id="speTolerance">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Traps Minimum (inches)</td>
          <td class="table_row"><span id="sprytextfield9">
            <input name="speTrapsMinimum" type="text" id="speTrapsMinimum">
          </span></td>
        </tr>

        <!-- speTrapsMaximum -->
        <tr>
          <td class="table_side_head">Traps Maximum (inches)</td>
          <td class="table_row"><span id="sprytextfield10">
            <input name="speTrapsMaximum" type="text" id="speTrapsMaximum">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Traps Metallic (inches)</td>
          <td class="table_row"><span id="sprytextfield11">
            <input name="speTrapsMetallic" type="text" id="speTrapsMetallic">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Traps Under Color Cut-Back (inches)</td>
          <td class="table_row"><span id="sprytextfield12">
            <input name="speTrapsUnderColorCutBack" type="text" id="speTrapsUnderColorCutBack">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Resolution: Lines per Inch</td>
          <td class="table_row"><span id="sprytextfield13">
            <input name="speResolution" type="text" id="speResolution">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Bleeds: External (inches)</td>
          <td class="table_row"><span id="sprytextfield14">
            <input name="speBleedsExternal" type="text" id="speBleedsExternal">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Dust Flaps (inches)</td>
          <td class="table_row"><span id="sprytextfield15">
            <input name="speBleedsDustFlaps" type="text" id="speBleedsDustFlaps">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Over Score (inches)</td>
          <td class="table_row"><span id="sprytextfield16">
            <input name="speBleedsOverScore" type="text" id="speBleedsOverScore">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Glue Flaps (inches)</td>
          <td class="table_row"><span id="sprytextfield17">
            <input name="speBleedsGlueFlaps" type="text" id="speBleedsGlueFlaps">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">UPC Barcode Minimum Magnification (%)</td>
          <td class="table_row"><span id="sprytextfield18">
            <input name="speUPCBarcodeMinMagnification" type="text" id="speUPCBarcodeMinMagnification">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">UPC Barcode BWR (inches)</td>
          <td class="table_row"><span id="sprytextfield19">
            <input name="speUPCBarcodeBWR" type="text" id="speUPCBarcodeBWR">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">UPC Barcode Orientation</td>
          <td class="table_row"><span id="sprytextfield20">
            <input name="speUPCBarcodeOrientation" type="text" id="speUPCBarcodeOrientation"></span></td>
        </tr>

        <tr>
          <td class="table_side_head">Rich Black Treatment?</td>
          <td class="table_row"><span id="spryradio7">
            <label>
              <input type="radio" name="speRichBlackTreatment" value="Yes" id="speRichBlackTreatment_0">
              Yes</label>
            <label>
              <input type="radio" name="speRichBlackTreatment" value="No" id="speRichBlackTreatment_1">
              No</label>
</span></td>
        </tr>

        <tr>
          <td class="table_side_head">What is the under color?</td>
          <td class="table_row"><span id="sprytextfield21">
            <input name="speRichBlackTreatmentUnderColor" type="text" id="speRichBlackTreatmentUnderColor">
</span></td>
        </tr>

        <tr>
          <td class="table_side_head">Do special scales or marks need to be added?</td>
          <td class="table_row"><span id="spryradio8">
            <label>
              <input type="radio" name="speScalesMarks" value="Yes" id="speScalesMarks_0">
              Yes</label>
            <label>
              <input type="radio" name="speScalesMarks" value="No" id="speScalesMarks_1">
              No</label>
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">If yes, please include instructions</td>
          <td class="table_row"><span id="sprytextfield22">
            <input name="speScalesMarksInstructions" type="text" id="speScalesMarksInstructions">
</span></td>
        </tr>
        <tr>
          <td class="table_divider" colspan="2">Proofing Requirements</td>
        </tr>
        <tr>
          <td class="table_row" colspan="2">If necessary, Galileo Global Branding Group, Inc. will fingerprint press before prepress begins.</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Type</td>
          <td class="table_row"><span id="spryselect4">
            <select name="speProofingReqProofType" id="speProofingReqProofType">
            	<option value="">Please choose...</option>
                <option value="Color Calibrated Epson">Color Calibrated Epson</option>
                <option value="Kodak Approval">Kodak Approval</option>
				<option value="Other">Other</option>
            </select>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Profile/Curve</td>
          <td class="table_row"><span id="spryselect5">
            <select name="speProofingReqProfileCurve" id="speProofingReqProfileCurve">
            	<option value="">Please choose...</option>
                <option value="Standard SWOP">Standard SWOP</option>
				<option value="F.I.R.S.T.">F.I.R.S.T.</option>
                <option value="Printer Specific">Printer Specific</option>
            </select>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">If yes, please include instructions</td>
          <td class="table_row"><span id="sprytextfield23">
            <input name="speProofingReqProfileCurveInstructions" type="text" id="speProofingReqProfileCurveInstructions">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Substrate</td>
          <td class="table_row"><span id="sprytextfield24">
            <input name="SpeProofSubstrate" type="text" id="SpeProofSubstrate">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Screening</td>
          <td class="table_row"><span id="spryselect6">
            <select name="speProofingReqScreening" id="speProofingReqScreening">
            	<option value="">Please choose...</option>
                <option value="Standard(C75 M45 Y90 K15 - round dot)">Standard(C75 M45 Y90 K15 - round dot)</option>
                <option value="Printer Specific">Printer Specific</option>
            </select>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">If yes, please include instructions</td>
          <td class="table_row"><span id="sprytextfield25">
            <input name="speProofingReqScreeningInstructions" type="text" id="speProofingReqScreeningInstructions">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Will this job require stepping?</td>
          <td class="table_row"><span id="spryradio9">
            <label>
              <input type="radio" name="speStepping" value="Yes" id="speStepping_0">
              Yes</label>
            <label>
              <input type="radio" name="speStepping" value="No" id="speStepping_1">
              No</label>
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">If yes, please include instructions</td>
          <td class="table_row"><span id="sprytextfield26">
            <input name="speSteppingInstructions" type="text" id="speSteppingInstructions">
</span></td>
        </tr>


        <tr>
          <td class="table_side_head_req">Final File Type</td>
          <td class="table_row"><span id="spryselect7">
            <select name="speFinalFileType" id="speFinalFileType">
                <option value="">Please choose...</option>
                <option value="Hi-Res Certified PDF">Hi-Res Certified PDF</option>
                <option value="DCS2 (single file)">DCS2 (single file)</option>
                <option value="DCS2 (multi file)">DCS2 (multi file)</option>
                <option value="1-bitt TIFF">1-bitt TIFF</option>
                <option value="Native ESKO">Native ESKO</option>
                <option value="Native Illustrator">Native Illustrator</option>
            </select>
          </span></td>
        </tr>


        <tr>
          <td class="table_divider" colspan="2">FTP</td>
        </tr>
        <tr>
          <td class="table_side_head">Do you accept final file delivery via FTP?</td>
          <td class="table_row"><span id="spryradio10">
            <label>
              <input type="radio" name="speFtp" value="Yes" id="speFtp_0">
              Yes</label>
            <label>
              <input type="radio" name="speFtp" value="No" id="speFtp_1">
              No</label>
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">If yes and you have your own site, please provide details:</td>
          <td class="table_row"></td>
        </tr>
        <tr>
          <td class="table_side_head">Host</td>
          <td class="table_row">
            <span id="sprytextfield27">
              <input name="speFtpHost" type="text" id="speFtpHost">
            </span>
          </td>
        </tr>
        <tr>
          <td class="table_side_head">User ID</td>
          <td class="table_row">
			<span id="sprytextfield28">
				<input name="speFtpUserID" type="text" id="speFtpUserID">
			</span>
		  </td>
        </tr>
        <tr>
          <td class="table_side_head">Password</td>
          <td class="table_row">
			<span id="sprytextfield29">
				<input name="speFtpPassword" type="text" id="speFtpPassword">
			</span>
			</td>
        </tr>
      </table>

	  <!--ATTACHMENT-->
	  <table id="attachment_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="table_header" colspan="2">Attachment</td>
	  </tr>
	  <tr>
		<td class="table_side_head">Description</td>
		<td class="table_row">
		  <span id="sprytextfield30">
		    <input type="text" name="PsaDescription" id="PsaDescription">
		  </span>
		</td>
	  </tr>
	  <tr>
		<td class="table_side_head">File</td>
		<td class="table_row">
		  <span id="new_item_file_name"><input type="file" name="file" id="file"></span>
		</td>
	  </tr>
	</table>
		<input type="hidden" name="statusComplete" value="" id="statusComplete">
		<input type="hidden" name="oURL" value="<?php echo $oURL; ?>" id="oURL">
		<input type="hidden" name="specID" value="new" id="specID">
		<input type="hidden" name="cmdupload" value="Save" />
		<input type="hidden" name="MAX_FILE_SIZE" value="6000000">
		<input type="hidden" name="printerName" value="<?php echo $_REQUEST['com']; ?>" id="printerName">
		<input type="hidden" name="p" value="<?php echo $_REQUEST['p']; ?>" id="p">
		<input type="hidden" name="upload" value="" id="upload">
		<input type="button" name="new_spec_save" value="Save" onclick="validateSave()" class='grayButton' />
		<input type="button" name="new_spec_submit" value="Complete" onclick="validateSubmit()" class='grayButton' />
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
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
var spryradio2 = new Spry.Widget.ValidationRadio("spryradio2");
var spryradio3 = new Spry.Widget.ValidationRadio("spryradio3");
var spryradio4 = new Spry.Widget.ValidationRadio("spryradio4");
var spryradio5 = new Spry.Widget.ValidationRadio("spryradio5");
var spryradio6 = new Spry.Widget.ValidationRadio("spryradio6");
var spryradio7 = new Spry.Widget.ValidationRadio("spryradio7", {isRequired:false});
var spryradio8 = new Spry.Widget.ValidationRadio("spryradio8", {isRequired:false});
var spryradio9 = new Spry.Widget.ValidationRadio("spryradio9", {isRequired:false});
var spryradio10 = new Spry.Widget.ValidationRadio("spryradio10", {isRequired:false});
var spryradio11 = new Spry.Widget.ValidationRadio("spryradio11");
var spryradio11 = new Spry.Widget.ValidationRadio("spryradio12");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {isRequired:false});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13", "none");
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none");
var sprytextfield15 = new Spry.Widget.ValidationTextField("sprytextfield15", "none", {isRequired:false});
var sprytextfield16 = new Spry.Widget.ValidationTextField("sprytextfield16", "none", {isRequired:false});
var sprytextfield17 = new Spry.Widget.ValidationTextField("sprytextfield17", "none", {isRequired:false});
var sprytextfield18 = new Spry.Widget.ValidationTextField("sprytextfield18");
var sprytextfield19 = new Spry.Widget.ValidationTextField("sprytextfield19");
var sprytextfield20 = new Spry.Widget.ValidationTextField("sprytextfield20", "none", {isRequired:false});
var sprytextfield21 = new Spry.Widget.ValidationTextField("sprytextfield21", "none", {isRequired:false});
var sprytextfield22 = new Spry.Widget.ValidationTextField("sprytextfield22", "none", {isRequired:false});
var sprytextfield23 = new Spry.Widget.ValidationTextField("sprytextfield23", "none", {isRequired:false});
var sprytextfield24 = new Spry.Widget.ValidationTextField("sprytextfield24");
var sprytextfield25 = new Spry.Widget.ValidationTextField("sprytextfield25", "none", {isRequired:false});
var sprytextfield26 = new Spry.Widget.ValidationTextField("sprytextfield26", "none", {isRequired:false});
var sprytextfield27 = new Spry.Widget.ValidationTextField("sprytextfield27", "none", {isRequired:false});
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28", "none", {isRequired:false});
var sprytextfield29 = new Spry.Widget.ValidationTextField("sprytextfield29", "none", {isRequired:false});
var sprytextfield30 = new Spry.Widget.ValidationTextField("sprytextfield30", "none", {isRequired:false});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
var spryselect6 = new Spry.Widget.ValidationSelect("spryselect6");
var spryselect7 = new Spry.Widget.ValidationSelect("spryselect7");
var spryselect8 = new Spry.Widget.ValidationSelect("spryselect8");
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("spec_tasks", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>

</body>
</html>
