<?php require_once('Connections/ContactLogin.php'); ?>

<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-Supplier');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Spec_find = $ContactLogin->newFindCommand('sup-Spec');
$Spec_findCriterions = array('_PrimeSpeIDX'=>'=='.fmsEscape($_REQUEST['s']),);
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

$Supplier__SupContact_portal = fmsRelatedRecord($Supplier_row, 'SupContact');
$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
$Supplier__SupDie_portal = fmsRelatedRecord($Supplier_row, 'SupDie');
$Supplier__SupImg_portal = fmsRelatedRecord($Supplier_row, 'SupImg');
 

$Spec_row = current($Spec_result->getRecords());

$Spec__JobUpcSpeCmpPRINTER_portal = fmsRelatedRecord($Spec_row, 'JobUpcSpeCmpPRINTER');
$Spec__calc_portal = fmsRelatedRecord($Spec_row, 'calc');
$Spec__JobUpcSpePsa_portal = fmsRelatedRecord($Spec_row, 'JobUpcSpePsa');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Assign Label Code</title>
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
<link href="SpryAssets/SpryMenuBar.js.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script type="text/javascript">
var uploadOrAssign = '';
var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
var newItemDescription = '<?php echo $_POST['newItemDescription']; ?>';
var newItemFileName = '<?php echo $_POST['newItemFileName']; ?>';

$(document).ready(function(){
	var n = $("#theList #itemContainer").length;
	$("#historical_LCI").hide(); // just so it doesn't show on load
	switchToUpload();
	$("#new_lci_table #addBtn").hide();
	/*
	alert("length: " + n);
	if(n > 0){
		$("#new_LCI").hide();
	} else {
		$("#historical_LCI").hide();
	}
	*/
	$("#addBtn a").hover(function() {
		$(this).next("em").animate({opacity: "show", top: "-75"}, "slow");
	}, function() {
		$(this).next("em").animate({opacity: "hide", top: "-85"}, "fast");
	});
	
	$("#theList div").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
    );
	
	if(infoPassFail){
		if(infoPassFail == "pass"){
			switchToAssign();
		} else {
			switchToUpload();
		}
	}
	
	$("#itemContainer #itemChecked").hide();
	$("ul#actions li#backToSpec").hide();
	setCheckboxDivs();
	gatherIDs();
	showInfoPanel();
});

// SHOW INFO PANEL
function showInfoPanel(){
	if (infoReturned){
		$("#infoPanel").text(infoReturned);
		//alert(newItemName);
		if(infoPassFail == "pass"){
			$("#infoPanel").addClass("pass");
			$("#theList #itemContainer").attr("name", "");
			$("#addBtn").hide();
			$("span#instructions").hide();
			$("input").hide();
			$("#new_item_description").text(newItemDescription);
			$("#new_item_file_name").text(newItemFileName);
			$("ul#actions li#save_item").hide();
			$("ul#actions li#cancel").hide();
			$("ul#actions li#backToSpec").show();
		} else {
			$("#infoPanel").addClass("errorVar");
		}
		$("#infoPanel").fadeIn();
	}
}

// CHECK TOGGLE
function checkToggle(obj){
	$("#theList div").removeClass("checked");
	$(obj).toggleClass("checked");
	//alert($("#theList #itemContainer").length);
	if($("#theList #itemContainer").length > 1){
		$("#theList #itemChecked").hide();
		$("#theList #itemUnchecked").show();
		$(obj).children("#itemUnchecked").hide();
		$(obj).children("#itemChecked").show();
	}
	gatherIDs();
}

// SET CHECKBOX DIVS
function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

// GATHER IDS
function gatherIDs(obj){
	var lci ='';
	$(".checked").each(function() {
    	lci += $(this).attr("value");	
	});
	$("#assigned_string").val(lci);
}

// OPEN DIELINE
function openDieline(url){
		window.open(url,'Download');
}

// SWITCH TO UPLOAD
function switchToUpload(){
	uploadOrAssign = 'upload';
	$("#historical_LCI").fadeOut('fast', function() {
    	$("#new_LCI").fadeIn('fast');
  });
}

// SWITCH TO ASSIGN
function switchToAssign(){
	uploadOrAssign = 'assign';
	$("#new_LCI").fadeOut('fast', function() {
    	$("#historical_LCI").fadeIn('fast');
  });
}

// VALIDATE ON SUBMIT
function validateOnSubmit(){
	var myForm=document.getElementById("upload_form");
	var SS= Spry.Widget.Form.validate(myForm);
	if(SS == true){
		myForm.submit();
	} else {
		flashNotice("NOTICE: There were issues saving this attachment", "Please enter valid data into the fields highlighted in red");
		highlightErrors();
	}
}

// SAVE BUTTON
function saveBtn(){
	if(uploadOrAssign=='upload'){
		validateOnSubmit();
	} else {
		document.assignment_form.submit();
	}
}
</script>

</head>

<body class="twoColHybLtHdr">


<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1>
      <!-- end #header -->
      New Printer Specification Attachment</h1>
  </div>

<!-- SIDEBAR -->
<div id="sidebar1">

    <ul id="supplier" class="MenuBarVertical">
		<li><a onclick="linkOutAlert('index.php')">Home</a></li>
    	<li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>')">Logout</a></li>
		<li><a onclick="linkOutAlert('supplier.php')">Update Company Contact Info</a></li>
		<li><a onclick="linkOutAlert('jobs.php')">Jobs</a></li>
		<li><a onclick="linkOutAlert('sku_list.php')">SKU List</a></li>
    </ul>
    
    <h1>Actions</h1>
  	<ul id="actions" class="MenuBarVertical">
        <li id="save_item"><a onclick="saveBtn()">Save</a></li>
        <li id="cancel"><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Cancel</a></li>
        <li id="backToSpec"><a href="spec.php?s=<?php echo $Spec_row->getField('_PrimeSpeIDX'); ?>">Back to Spec</a></li>
  	</ul>

<!-- end #sidebar1 --></div>
 

<!-- MAIN CONTENT -->
  <div id="mainContent">
    <!-- INFO PANEL --> 
    <div id="infoPanel" class=""></div>
	
	<!--SPEC INFO-->
    <table id="spec_info" class="table" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="table_header" colspan="2">Specification Info</td>
      </tr>
      <tr>
        <td class="table_side_head">Printer</td>
        <td class="table_row"><?php echo $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Specification Name</td>
        <td class="table_row"><?php echo $Spec_row->getField('speName'); ?></td>
      </tr>
    </table>
    
<!-- NEW PSA UPLOAD FORM -->
<div id="new_PSA">
<form action="psa_new_response.php" method=post enctype="multipart/form-data" name=upload_form id="upload_form">

<table id="new_lci_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="table_header" colspan="2">New Attachment</td>
  </tr>
  <tr>
    <td class="table_side_head_req">Description</td>
    <td class="table_row"><span id="sprytextfield2">
      <input type="text" name="PsaDescription" id="PsaDescription">
      </span><span id="new_item_description"></span></td>
  </tr>
  <tr>
    <td class="table_side_head_req">File</td>
    <td class="table_row"><input type="file" name="file" id="file"><span id="new_item_file_name"></span></td>
  </tr>
</table>
<input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="redirect">
<input type="hidden" name="cmdupload" value="Save" />
<input type="hidden" name="MAX_FILE_SIZE" value="6000000">
<input type="hidden" name="psa_FK_spe" value="<?php echo $Spec_row->getField('_PrimeSpeIDX'); ?>" id="psa_FK_spe">
<input type="hidden" name="specName" value="<?php echo $Spec_row->getField('speName'); ?>" id="specName">
<input type="hidden" name="printerName" value="<?php echo $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompanyFolderName'); ?>" id="printerName">

<input type="button" name="new_psa" id="new_psa" value="Save New Attachment" onClick="validateOnSubmit();">
</form>
</div>

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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
