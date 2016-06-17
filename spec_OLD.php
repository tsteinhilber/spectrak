<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.$_SESSION['cmp_ID'],);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Spec_find = $ContactLogin->newFindCommand('sup-Spec');
$Spec_findCriterions = array('_PrimeSpeIDX'=>fmsEscape($_REQUEST['s']),);
foreach($Spec_findCriterions as $key=>$value) {
    $Spec_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Spec_find,'Spec',1); 

$Supplier_result = $Supplier_find->execute(); 

$Spec_result = $Spec_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($Spec_result)) fmsTrapError($Spec_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($Spec_result,'Spec',1); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

$Spec_row = current($Spec_result->getRecords());

$Spec__JobUpcSpePsa_portal = fmsRelatedRecord($Spec_row, 'JobUpcSpePsa');
$Spec__JobUpcSpeCmpPRINTER_portal = fmsRelatedRecord($Spec_row, 'JobUpcSpeCmpPRINTER');
$Spec__calc_portal = fmsRelatedRecord($Spec_row, 'calc');
 
// VARS 
$printerID = $Spec_row->getField('spe_FK_cmp');
$printerName = $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompany');
$specID = $Spec_row->getField('_PrimeSpeIDX');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php
$Contacts_find = $ContactLogin->newFindCommand('sup-Contact');
$Contacts_findCriterions = array('con_FK_cmp'=>$Spec_row->getField('spe_FK_cmp'),);
foreach($Contacts_findCriterions as $key=>$value) {
    $Contacts_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contacts_find,'Contacts',500); 

$Contacts_find->addSortRule('conNameL', 1, FILEMAKER_SORT_ASCEND);

$Contacts_result = $Contacts_find->execute(); 

if(FileMaker::isError($Contacts_result)) fmsTrapError($Contacts_result,"error.php"); 

fmsSetLastPage($Contacts_result,'Contacts',500); 

$Contacts_row = current($Contacts_result->getRecords());

$Contacts__calc_portal = fmsRelatedRecord($Contacts_row, 'calc');
$Contacts__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTER');
$Contacts__SupJobCmpPRINTERConAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERConAdd');
$Contacts__Sup_portal = fmsRelatedRecord($Contacts_row, 'Sup');
$Contacts__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERAdd');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php 
// FIND ADDRESSES
$Addresses_find = $ContactLogin->newFindCommand('sup-Address');
$Addresses_findCriterions = array('add_FK_cmp'=>$printerID,);
foreach($Addresses_findCriterions as $key=>$value) {
    $Addresses_find->AddFindCriterion($key,$value);
}

fmsSetPage($Addresses_find,'Addresses',20); 

$Addresses_result = $Addresses_find->execute(); 

if(FileMaker::isError($Addresses_result)) fmsTrapError($Addresses_result,"error.php"); 

fmsSetLastPage($Addresses_result,'Addresses',20); 

$Addresses_row = current($Addresses_result->getRecords());
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | <?php echo $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?> Spec: <?php echo $Spec_row->getField('speName'); ?></title>
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

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script language="JavaScript">
$(document).ready(function(){
	var contactID = '<? echo $_REQUEST['newContactID']; ?>';
	var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
	var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
	var lockState = "<?php echo $Spec_row->getField('speWebLock'); ?>";
	
	var colorsNum = $("#speMaxNumColors").val();
	var speSubstrate = $("#speSubstrate").val();
	var reqProfileCurve = $("#speProofingReqProfileCurve").val();
	var screening = $("#speProofingReqScreening").val();
	
	locked(lockState);

	// Hide New Contact Table
	$("#new_contact").hide();
	
	// Hide Backgrounder
	$("#backgrounder").hide();
	
	// Select newContactID
	if(contactID){
		$("#Spe_ConIDX").val(contactID);
		toggleInfoPanel(infoReturned, infoPassFail);
	}
	
	// speMinDot - if yes, show %
	if($('input[name=speMinDot]:checked').val() == 'Yes'){
		$('input[name=speMinDot]').next().next().show();
	} else {
		$('input[name=speMinDot]').next().next().hide();
	}
	
	// speMinDot - show/hide % on change
	$('input[name=speMinDot]').change(function() {
	  var itemValue = $("input[name='speMinDot']:checked").val();
	  if(itemValue == 'Yes'){
		$('input[name=speMinDot]').next().next().show();
		$('#speMinDotPerc').css('background-color', '#FFFFCC').focus();
	  } else {
		  $('input[name=speMinDot]').next().next().hide();
	  }
	});
	
	// speScalesMarks
	if($('input[name=speScalesMarks]:checked').val() == 'Yes'){
		$('input[name=speScalesMarks]').next().next().show();
	} else {
		$('input[name=speScalesMarks]').next().next().hide();
	}
	
	$('input[name=speScalesMarks]').change(function() {
	  var itemValue = $('input[name=speScalesMarks]:checked').val();
	  if(itemValue == 'Yes'){
		$('input[name=speScalesMarks]').next().next().show();
		$('#speScalesMarksInstructions').css('background-color', '#FFFFCC').focus();
	  } else {
		  $('input[name=speScalesMarks]').next().next().hide();
	  }
	});
	
	// speStepping
	if($('input[name=speStepping]:checked').val() == 'Yes'){
		$('input[name=speStepping]').next().next().show();
	} else {
		$('input[name=speStepping]').next().next().hide();
	}
	
	$('input[name=speStepping]').change(function() {
	  var itemValue = $('input[name=speStepping]:checked').val();
	  if(itemValue == 'Yes'){
		$('input[name=speStepping]').next().next().show();
		$('#speSteppingInstructions').css('background-color', '#FFFFCC').focus();
	  } else {
		  $('input[name=speStepping]').next().next().hide();
	  }
	});
	
	// speFtp
	if($('input[name=speFtp]:checked').val() == 'Yes'){
		$('#ftp2').show();
		$('#ftp3').show();
		$('#ftp4').show();
	} else {
		$('#ftp2').hide();
		$('#ftp3').hide();
		$('#ftp4').hide();
	}
	
	$('input[name=speFtp]').change(function() {
	  var itemValue = $('input[name=speFtp]:checked').val();
	  if(itemValue == 'Yes'){
		$('#ftp2').show();
		$('#ftp3').show();
		$('#ftp4').show();
		$("#speFtpHost").focus();
	  } else {
		$('#ftp2').hide();
		$('#ftp3').hide();
		$('#ftp4').hide();
	  }
	});
	
	// Initial Toggle numberOfColors
	if(colorsNum == "4-color process plus" || colorsNum == "Spot colors ONLY"){
		$("#numberOfColors").show();
	} else {
		$("#numberOfColors").hide();
	}
	
	// Toggle numberOfColors
	$("#speMaxNumColors").change(function(){
		colorsNum = $("#speMaxNumColors").val();
		//alert(colorsNum);
		if(colorsNum == "4-color process plus"  || colorsNum == "Spot colors ONLY"){
			$("#numberOfColors").show();
		} else {
			$("#numberOfColors").hide();
		}
	});
	
	// Initial Toggle speSubstrateOther
	if(speSubstrate == "Other"){
		$("#speSubstrateOther").show();
	} else {
		$("#speSubstrateOther").hide();
	}
	
	// Toggle speSubstrateOther
	$("#speSubstrate").change(function(){
		var speSubstrate = $("#speSubstrate").val();
		if(speSubstrate == "Other"){
			$("#speSubstrateOther").show();
		} else {
			$("#speSubstrateOther").hide();
		}
	});
	
	// Initial Toggle reqProfileCurve
	if(reqProfileCurve == "Printer Specific"){
		$("#speProofingReqProfileCurveInstructions").show();
	} else {
		$("#speProofingReqProfileCurveInstructions").hide();
	}

	// Toggle reqProfileCurve
	$("#speProofingReqProfileCurve").change(function(){
		reqProfileCurve = $("#speProofingReqProfileCurve").val();
		if(reqProfileCurve == "Printer Specific"){
			$("#speProofingReqProfileCurveInstructions").show();
		} else {
			$("#speProofingReqProfileCurveInstructions").hide();
		}
	});
	
	// Initial Toggle screening
	if(screening == "Printer Specific"){
		$("#speProofingReqScreeningInstructions").show();
	} else {
		$("#speProofingReqScreeningInstructions").hide();
	}

	// Toggle screening
	$("#speProofingReqScreening").change(function(){
		screening = $("#speProofingReqScreening").val();
		// alert(screening);
		if(screening == "Printer Specific"){
			$("#speProofingReqScreeningInstructions").show();
		} else {
			$("#speProofingReqScreeningInstructions").hide();
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

// LOCKED
function locked(lock){
	if(lock == 1){
		$(":input[type='hidden']").remove();
		$(":input").attr("disabled", "disabled").show();
		$("#mainContent > h1").append("<span class='locked'> - Locked</span>");
		$("#save, #complete, #addAttachment, #addBtn, .grayButton").hide();
	}
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
	var myForm = document.getElementById("edit_spec");
	validateSpec();
	if(spec_validation){
		myForm.submit();
	} else {
		flashNotice("NOTICE: There were issues creating this specification", "Please enter valid data into the fields highlighted in red");
	}
}

// VALIDATE SUBMIT
function validateSubmit(){
	var myForm = document.getElementById("edit_spec");
	var SS = Spry.Widget.Form.validate(myForm);
	coating = $("input[name='speCoating']:checked").val();
	whiteInk = $("input[name='speWhiteInk']:checked").val();
	extraStation = $("input[name='speExtraStation']:checked").val();
	gradations = $("input[name='speGradations']:checked").val();
	minDot = $("input[name='speMinDot']:checked").val();
	documentRasterEffects = $("input[name='speDocumentRasterEffects']:checked").val();
	abobeIllustratorTransparencyEffects = $("input[name='speAbobeIllustratorTransparencyEffects']:checked").val();
	
	proofingReqProofType = $("#speProofingReqProofType option:selected").val();
	proofingReqProfileCurve = $("#speProofingReqProfileCurve option:selected").val();
	proofingReqProfileCurveInstructions = $("#speProofingReqProfileCurveInstructions").val();
	proofingReqScreening = $("#speProofingReqScreening option:selected").val();
	proofingReqScreeningInstructions = $("#speProofingReqScreeningInstructions").val();
	finalFileType = $("#speFinalFileType option:selected").val();
	
	validateSpec();
	
	// coating validation
	if(!coating) {
		$("input[name='speCoating']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// whiteInk validation
	if(!whiteInk) {
		$("input[name='speWhiteInk']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// extraStation validation
	if(!extraStation) {
		$("input[name='speExtraStation']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// gradations validation
	if(!gradations) {
		$("input[name='speGradations']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// minDot validation
	if(!minDot) {
		$("input[name='speMinDot']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	} 
	
	// documentRasterEffects validation
	if(!documentRasterEffects) {
		$("input[name='speDocumentRasterEffects']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// abobeIllustratorTransparencyEffects validation
	if(!abobeIllustratorTransparencyEffects) {
		$("input[name='speAbobeIllustratorTransparencyEffects']").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// proofingReqProofType validation
	if(!proofingReqProofType) {
		$("#speProofingReqProofType").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// proofingReqProfileCurve validation
	if(!proofingReqProfileCurve) {
		$("#speProofingReqProfileCurve").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}

	// proofingReqProfileCurveInstructions validation
	if(proofingReqProfileCurve && !proofingReqProfileCurveInstructions) {
		$("#speProofingReqProfileCurveInstructions").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// proofingReqScreening validation
	if(!proofingReqScreening) {
		$("#speProofingReqScreening").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// proofingReqScreeningInstructions validation
	if(proofingReqScreening && !proofingReqScreeningInstructions) {
		$("#speProofingReqScreeningInstructions").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
	// finalFileType validation
	if(!finalFileType) {
		$("#speFinalFileType").parent().parent().children("td").css("background-color","#FF99A8");
		spec_validation = false;
	}
	
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

<body class="twoColHybLtHdr" id="spec">
<div id='backgrounder'></div>

<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a onclick="linkOutAlert('index.php');"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1> <?php echo $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?>: Specification</h1>
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
	<li id="addAttachment"><a href="psa_new.php?s=<?php echo $_GET['s']; ?>">Add New Attachment</a></li>
	<li id="save"><a onclick="validateSave()">Save</a></li>
	<li id="complete"><a onclick="validateSubmit()">Complete</a></li>
	<li><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Cancel</a></li>
</ul>

<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->  
<div id="mainContent">

<!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>
	
	<h1><?php echo $Spec_row->getField('speName'); ?></h1>

	<!-- INSTRUCTIONS -->
    <p><span id='instructions'><span class="callout">INSTRUCTIONS:</span> Please enter item information in the fields below. <em>Print Process, Substrate and Colors Allowed</em> fields are required to Save. Select <em>Save</em> to if you would like to come back and complete the form at a later time. Select <em>Complete</em> if the information is complete.</span></p>
	
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
		  <td class="table_row">
			<select name="con_FK_add" id="con_FK_add">
				<option value="">Please choose...</option>
				<?php foreach($Addresses_result->getRecords() as $Add_row){ ?>
					<option value="<?php echo $Add_row->getField('add_PK'); ?>">
						<?php echo $Add_row->getField('addAddress'); ?> <?php echo $Add_row->getField('addCityStateZip'); ?>
					</option>
				<?php } ?>
			</select>
		  </td>
		</tr>
		</table>
		<input type="hidden" name="oURL" value="<?php echo $oURL; ?>" id="oURL">
		<input type="hidden" name="specID" value="new" id="specID">
		<input type="hidden" name="cmp_PK" value="<?php echo $printerID; ?>" id="cmp_PK">
		<input type="button" id="new_contact_save" value="Save New Contact" class='grayButton' />
		<input type="button" name="new_contact_cancel" value="Cancel" class='grayButton' />
	</form>
	</div>	

<!-- SPECIFICATIONS FORM -->
	<form action="spec_edit_response.php" method="post" name="edit_spec" id="edit_spec">
      <table id="specifications" class="table" width="100%" cellpadding="0" cellspacing="0">

<!-- CONTACT -->
		<tr>
          <td colspan="2" class="table_header">Printer Specification</td>
        </tr>
		<tr>
			<td class="table_side_head_req">Printer Contact Assignment</td>
			<td class="table_row">
				<span id="spryselect5">
				<select name="Spe_ConIDX" id="Spe_ConIDX">
					<option value="">-- Please Select --</option>
					<option value="new">Add New Contact</option>
					<option disabled="disabled">—————————————</option>
					<?php foreach($Contacts_result->getRecords() as $Contacts_row){ 
						$selected = "";
						if ( $Contacts_row->getField('con_PK') == $Spec_row->getField('Spe_ConIDX') ) {
							$selected = "SELECTED";
						}
					?>
	    			<option value="<?php echo $Contacts_row->getField('con_PK'); ?>" <?php echo $selected ?> ><?php echo $Contacts_row->getField('conNameF'); ?> <?php echo $Contacts_row->getField('conNameL'); ?></option>
	    			<?php } ?>
			    </select>
			</span></td>
		</tr>
		
<!-- SPEC -->
		<tr>
          <td colspan="2" class="table_divider">GENERAL SPECIFICATIONS</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Print Process</td>
          <td class="table_row"><span id="spryselect1">
            <select name="spePrintProcess" id="spePrintProcess" size="1">
              <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','spePrintProcess',"",null,$Spec_row->getField('spePrintProcess')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('spePrintProcess')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
            </select>
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Substrate</td>
          <td class="table_row"><span id="spryselect2">
            <select name="speSubstrate" id="speSubstrate">
              <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speSubstrate',"",null,$Spec_row->getField('speSubstrate')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speSubstrate')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
            </select>
			<input name="speSubstrateOther" type="text" value="<?php echo $Spec_row->getField('speSubstrateOther'); ?>" id="speSubstrateOther" class="input_50perc">
          <span class="selectRequiredMsg">Please select an item.</span></span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Colors Allowed</td>
          <td class="table_row"><span id="spryselect3">
            <select name="speMaxNumColors" id="speMaxNumColors">
              <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speMaxNumColors',"",null,$Spec_row->getField('speMaxNumColors')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speMaxNumColors')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
            </select>
          </span>
          
		  <span id="numberOfColors">
          <span id="sprytextfield1">
          <input REQUIRED name="speMaxNumColorsSpotNum" type="text" value="<?php echo $Spec_row->getField('speMaxNumColorsSpotNum'); ?>" id="speMaxNumColorsSpotNum" class="input_small">
          </span>
          
          spots (one number)</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Ink Sequence</td>
          <td class="table_row"><span id="sprytextfield2">
            <input name="speInkSequence" type="text" value="<?php echo $Spec_row->getField('speInkSequence'); ?>" id="speInkSequence">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Are items printed on Surface or Inside?</td>
          <td class="table_row"><span id="spryselect4">
            <select name="spePrintSurfaceOrInside" id="spePrintSurfaceOrInside">
              <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','spePrintSurfaceOrInside',"",null,$Spec_row->getField('spePrintSurfaceOrInside')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('spePrintSurfaceOrInside')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
            </select>
          <span class="selectRequiredMsg">Please select an item.</span></span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is there a Varnish or Aqueous Coating on this item?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speCoating')) ) {
    echo "<input name=\"speCoating\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speCoating\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is white ink required?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speWhiteInk')) ) {
    echo "<input name=\"speWhiteInk\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speWhiteInk\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is a screen of a spot considered an extra station on press?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speExtraStation')) ) {
    echo "<input name=\"speExtraStation\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speExtraStation\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Are gradations feasible with this printing method?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speGradations')) ) {
    echo "<input name=\"speGradations\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speGradations\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Is there a minimum dot required?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speMinDot')) ) {
    echo "<input name=\"speMinDot\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speMinDot\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?> <p>What is the minimum dot? (%): <input name="speMinDotPerc" type="text" value="<?php echo $Spec_row->getField('speMinDotPerc'); ?>" id="speMinDotPerc" class="input_50perc"></p>
</td>
        </tr>
		<tr>
			<td class="table_side_head_req">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle document raster effects?</td>
			<td class="table_row">
		    <?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speDocumentRasterEffects')) ) {
    echo "<input name=\"speDocumentRasterEffects\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speDocumentRasterEffects\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
		</tr>
		<tr>
			<td class="table_side_head_req">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle Adobe Illustrator transparency effects?</td>
			<td class="table_row">
		    <?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speAbobeIllustratorTransparencyEffects')) ) {
    echo "<input name=\"speAbobeIllustratorTransparencyEffects\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speAbobeIllustratorTransparencyEffects\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
		</tr>
        <tr class="table_sub_head">
          <td class="table_divider" colspan="2">Print Limitations:</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Rule Weight: Positive (pt)</td>
          <td class="table_row"><span id="sprytextfield4">
            <input name="speMinRuleWeightPositive" type="text" value="<?php echo $Spec_row->getField('speMinRuleWeightPositive'); ?>" id="speMinRuleWeightPositive">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Rule Weight: Negative (pt)</td>
          <td class="table_row"><span id="sprytextfield5">
            <input name="speMinRuleWeightNegative" type="text" value="<?php echo $Spec_row->getField('speMinRuleWeightNegative'); ?>" id="speMinRuleWeightNegative">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Type Size: Positive Copy (pt)</td>
          <td class="table_row"><span id="sprytextfield6">
            <input name="speMinTypeSizePositive" type="text" value="<?php echo $Spec_row->getField('speMinTypeSizePositive'); ?>" id="speMinTypeSizePositive">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Minimum Type Size: Reverse Copy (pt)</td>
          <td class="table_row"><span id="sprytextfield7">
            <input name="speMinTypeSizeReverse" type="text" value="<?php echo $Spec_row->getField('speMinTypeSizeReverse'); ?>" id="speMinTypeSizeReverse">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Tolerance: Copy clearance from trims &amp; folds</td>
          <td class="table_row"><span id="sprytextfield8">
            <input name="speTolerance" type="text" value="<?php echo $Spec_row->getField('speTolerance'); ?>" id="speTolerance">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Traps Minimum (inches)</td>
          <td class="table_row"><span id="sprytextfield9">
            <input name="speTrapsMinimum" type="text" value="<?php echo $Spec_row->getField('speTrapsMinimum'); ?>" id="speTrapsMinimum">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">Traps Maximum (inches)</td>
          <td class="table_row"><span id="sprytextfield10">
            <input name="speTrapsMaximum" type="text" value="<?php echo $Spec_row->getField('speTrapsMaximum'); ?>" id="speTrapsMaximum">
		  </span></td>
        </tr>
        <tr>
          <td class="table_side_head">Traps Metallic (inches)</td>
          <td class="table_row"><span id="sprytextfield11">
            <input name="speTrapsMetallic" type="text" value="<?php echo $Spec_row->getField('speTrapsMetallic'); ?>" id="speTrapsMetallic">
		  </span></td>
        </tr>
        <tr>
          <td class="table_side_head">Traps Under Color Cut-Back (inches)</td>
          <td class="table_row"><span id="sprytextfield12">
            <input name="speTrapsUnderColorCutBack" type="text" value="<?php echo $Spec_row->getField('speTrapsUnderColorCutBack'); ?>" id="speTrapsUnderColorCutBack">
		  </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Resolution: Lines per Inch</td>
          <td class="table_row"><span id="sprytextfield13">
            <input name="speResolution" type="text" value="<?php echo $Spec_row->getField('speResolution'); ?>" id="speResolution">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Bleeds: External (inches)</td>
          <td class="table_row"><span id="sprytextfield14">
            <input name="speBleedsExternal" type="text" value="<?php echo $Spec_row->getField('speBleedsExternal'); ?>" id="speBleedsExternal">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Dust Flaps (inches)</td>
          <td class="table_row"><span id="sprytextfield15">
            <input name="speBleedsDustFlaps" type="text" value="<?php echo $Spec_row->getField('speBleedsDustFlaps'); ?>" id="speBleedsDustFlaps">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Over Score (inches)</td>
          <td class="table_row"><span id="sprytextfield16">
            <input name="speBleedsOverScore" type="text" value="<?php echo $Spec_row->getField('speBleedsOverScore'); ?>" id="speBleedsOverScore">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Bleeds: Glue Flaps (inches)</td>
          <td class="table_row"><span id="sprytextfield17">
            <input name="speBleedsGlueFlaps" type="text" value="<?php echo $Spec_row->getField('speBleedsGlueFlaps'); ?>" id="speBleedsGlueFlaps">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">UPC Barcode Minimum Magnification (%)</td>
          <td class="table_row"><span id="sprytextfield18">
            <input name="speUPCBarcodeMinMagnification" type="text" value="<?php echo $Spec_row->getField('speUPCBarcodeMinMagnification'); ?>" id="speUPCBarcodeMinMagnification">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">UPC Barcode BWR (inches)</td>
          <td class="table_row"><span id="sprytextfield19">
            <input name="speUPCBarcodeBWR" type="text" value="<?php echo $Spec_row->getField('speUPCBarcodeBWR'); ?>" id="speUPCBarcodeBWR">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head">UPC Barcode Orientation</td>
          <td class="table_row"><span id="sprytextfield20">
            <input name="speUPCBarcodeOrientation" type="text" value="<?php echo $Spec_row->getField('speUPCBarcodeOrientation'); ?>" id="speUPCBarcodeOrientation">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Rich Black Treatment?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speRichBlackTreatment')) ) {
    echo "<input name=\"speRichBlackTreatment\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speRichBlackTreatment\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr>
          <td class="table_side_head">What is the under color?</td>
          <td class="table_row"><span id="sprytextfield21">
            <input name="speRichBlackTreatmentUnderColor" type="text" value="<?php echo $Spec_row->getField('speRichBlackTreatmentUnderColor'); ?>" id="speRichBlackTreatmentUnderColor">
</span></td>
        </tr>
        <tr>
          <td class="table_side_head">Do special scales or marks need to be added?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speScalesMarks')) ) {
    echo "<input name=\"speScalesMarks\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speScalesMarks\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?> <span>| Please include instructions: <input name="speScalesMarksInstructions" type="text" value="<?php echo $Spec_row->getField('speScalesMarksInstructions'); ?>" id="speScalesMarksInstructions" class="input_50perc"></span></td>
        </tr>
        <tr>
          <td class="table_divider" colspan="2">Proofing Requirements</td>
        </tr>
        <tr>
          <td class="table_row" colspan="2">If necessary, Galileo Global Branding Group, Inc. will fingerprint press before prepress begins.</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Type</td>
          <td class="table_row"><select name="speProofingReqProofType" id="speProofingReqProofType">
            <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speProofingReq',"",null,$Spec_row->getField('speProofingReqProofType')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speProofingReqProofType')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
          </select></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Profile/Curve</td>
          <td class="table_row"><select name="speProofingReqProfileCurve" id="speProofingReqProfileCurve">
            <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speProofingReqProfileCurve',$Spec_row->getField('speProofingReqProfileCurve'),null,$Spec_row->getField('speProofingReqProfileCurve')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speProofingReqProfileCurve')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
          </select>
				<p id="speProofingReqProfileCurveInstructions">
					If yes, please include instructions 
		        	<input name="speProofingReqProfileCurveInstructions" type="text" value="<?php echo $Spec_row->getField('speProofingReqProfileCurve'); ?>" id="speProofingReqProfileCurveInstructions" class="input_40perc"> 
				</p>
			</td>
        </tr>
        <tr>
          <td class="table_side_head_req">Substrate</td>
          <td class="table_row"><span id="sprytextfield24">
            <input name="SpeProofSubstrate" type="text" value="<?php echo $Spec_row->getField('SpeProofSubstrate'); ?>" id="SpeProofSubstrate">
          </span></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Screening</td>
          <td class="table_row"><select name="speProofingReqScreening" id="speProofingReqScreening">
            <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speProofingReqScreening',"",null,$Spec_row->getField('speProofingReqScreening')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speProofingReqScreening')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
          </select>
			<p id="speProofingReqScreeningInstructions">
	            If yes, please include instructions 
				<input name="speProofingReqScreeningInstructions" type="text" value="<?php echo $Spec_row->getField('speProofingReqScreeningInstructions'); ?>" id="speProofingReqScreeningInstructions" class="input_40perc"> 
			</p>
			</td>
        </tr>
        <tr>
          <td class="table_side_head">Will this job require stepping?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speStepping')) ) {
    echo "<input name=\"speStepping\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speStepping\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?> <p>Please include instructions: <input name="speSteppingInstructions" type="text" value="<?php echo $Spec_row->getField('speSteppingInstructions'); ?>" id="speSteppingInstructions" class="input_50perc"></p></td>
        </tr>
        <tr>
          <td class="table_side_head_req">Final File Type</td>
          <td class="table_row"><select name="speFinalFileType" id="speFinalFileType">
            <?php
foreach(fmsValueListItems2($ContactLogin,'sup-Spec','speFinalFileType',"",null,$Spec_row->getField('speFinalFileType')) as $list_item) {
  if(html_entity_decode($list_item[0]) == $Spec_row->getField('speFinalFileType')) {
    echo "<option value=\"{$list_item[0]}\" selected=\"selected\">{$list_item[1]}</option>\n";
  } else {
    echo "<option value=\"{$list_item[0]}\">{$list_item[1]}</option>\n";
  }
}
?>
          </select></td>
        </tr>
        <tr>
          <td class="table_divider" colspan="2">FTP</td>
        </tr>
        <tr>
          <td class="table_side_head">Do you accept final file delivery via FTP?</td>
          <td class="table_row"><?php
foreach(fmsValueListItems($ContactLogin,'sup-Spec','Yes/No',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $Spec_row->getField('speFtp')) ) {
    echo "<input name=\"speFtp\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"speFtp\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
        </tr>
        <tr id="ftp2">
          <td class="table_side_head">Host</td>
          <td class="table_row"><span id="sprytextfield27">
            <input name="speFtpHost" type="text" value="<?php echo $Spec_row->getField('speFtpHost'); ?>" id="speFtpHost">
          </span></td>
        </tr>
        <tr id="ftp3">
          <td class="table_side_head">User ID</td>
          <td class="table_row"><span id="sprytextfield28">
            <input name="speFtpUserID" type="text" value="<?php echo $Spec_row->getField('speFtpUserID'); ?>" id="speFtpUserID">
          </span></td>
        </tr>
        <tr id="ftp4">
          <td class="table_side_head">Password</td>
          <td class="table_row"><span id="sprytextfield29">
            <input name="speFtpPassword" type="text" value="<?php echo $Spec_row->getField('speFtpPassword'); ?>" id="speFtpPassword">
          </span></td>
        </tr>
      </table>
	  
	  <!--ATTACHMENTS-->
	  <table id="attachments" class="table" width="100%" cellpadding="0" cellspacing="0">
	  <tr>
	    <td class="table_header" colspan="3">Attachments
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('psa_new.php?s=<?php echo $_GET['s']; ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({ 
					show: 'mouseover',
					hide: 'mouseout',
					content: 'Add New Attachment',
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
	  
	  <?php if(!$Spec__JobUpcSpePsa_portal->getField('JobUpcSpePsa::_PrimePsaIDX')){ ?>
	  <tr>
	    <td class="table_row_instr" colspan="3">No attachments are currently assigned to this specification</td>
      </tr>
	  <?php } ?>
	  
	  <?php
$Spec__JobUpcSpePsa_portal_rownum = 1;
foreach(fmsRelatedSet($Spec_row,'JobUpcSpePsa') as $Spec__JobUpcSpePsa_portal_row=>$Spec__JobUpcSpePsa_portal){ 

	$attachment = $Spec__JobUpcSpePsa_portal->getField('JobUpcSpePsa::_PrimePsaIDX');
	$attachment_path = $Spec__JobUpcSpePsa_portal->getField('JobUpcSpePsa::PsaPath');

?>
	    <tr>
	      <td class="table_row_borderR_arrow" width="40%"><?php echo $Spec__JobUpcSpePsa_portal->getField('JobUpcSpePsa::PsaFileName'); ?></td>
		  <td class="table_row_borderR"><?php echo $Spec__JobUpcSpePsa_portal->getField('JobUpcSpePsa::PsaDescription'); ?></td>
	      <td class="table_row" width="18"><a href="<?php echo $attachment_path; ?>" target="_blank"><img src="img/Zoom In.png" alt="attachment image" width="16" height="16" title="View Attachment"></a></td>
        </tr>
	    <?php if($Spec__JobUpcSpePsa_portal_rownum == 0) break; else $Spec__JobUpcSpePsa_portal_rownum++;
}//portal_end ?>
    </table>
      <div>
        <input name="statusComplete" type="hidden" value="" id="statusComplete">
		<input name="p" type="hidden" value="<?php echo $Spec_row->getField('spe_FK_cmp'); ?>" id="p">
        <input name="-recid" type="hidden" value="<?php echo $Spec_row->getRecordId(); ?>" id="-recid">
		<input type="button" name="new_spec_save" value="Save" onclick="validateSave()" class="grayButton" />
		<input type="button" name="new_spec_submit" value="Complete" onclick="validateSubmit()" class="grayButton" />
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
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur"], isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {isRequired:false});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13");
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14");
var sprytextfield15 = new Spry.Widget.ValidationTextField("sprytextfield15", "none", {isRequired:false});
var sprytextfield16 = new Spry.Widget.ValidationTextField("sprytextfield16", "none", {isRequired:false});
var sprytextfield17 = new Spry.Widget.ValidationTextField("sprytextfield17", "none", {isRequired:false});
var sprytextfield18 = new Spry.Widget.ValidationTextField("sprytextfield18");
var sprytextfield19 = new Spry.Widget.ValidationTextField("sprytextfield19");
var sprytextfield20 = new Spry.Widget.ValidationTextField("sprytextfield20", "none", {isRequired:false});
var sprytextfield21 = new Spry.Widget.ValidationTextField("sprytextfield21", "none", {isRequired:false});
var sprytextfield24 = new Spry.Widget.ValidationTextField("sprytextfield24");
var sprytextfield27 = new Spry.Widget.ValidationTextField("sprytextfield27", "none", {isRequired:false});
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28", "none", {isRequired:false});
var sprytextfield29 = new Spry.Widget.ValidationTextField("sprytextfield29", "none", {isRequired:false});
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("spec_tasks", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
