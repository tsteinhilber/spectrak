<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$UPC_find = $ContactLogin->newFindCommand('sup-UPC');
$UPC_findCriterions = array('upc_PK'=>$_REQUEST['u'],);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

$PDK_find = $ContactLogin->newFindCommand('sup-PDK');
$PDK_findCriterions = array('pdk_FK_upc'=>$_REQUEST['u'],);
foreach($PDK_findCriterions as $key=>$value) {
    $PDK_find->AddFindCriterion($key,$value);
}

fmsSetPage($PDK_find,'PDK',1);

fmsSetPage($UPC_find,'UPC',1);

$UPC_result = $UPC_find->execute();

$PDK_result = $PDK_find->execute();

if(FileMaker::isError($PDK_result)) fmsTrapError($PDK_result,"error.php");

fmsSetLastPage($PDK_result,'PDK',1);

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php");

fmsSetLastPage($UPC_result,'UPC',1);

$UPC_row = current($UPC_result->getRecords());

$UPC__JobUpcSku_portal = fmsRelatedRecord($UPC_row, 'JobUpcSku');
$UPC__JobCmpSUPPLIER_portal = fmsRelatedRecord($UPC_row, 'JobCmpSUPPLIER');
$UPC__calc_portal = fmsRelatedRecord($UPC_row, 'calc');
$UPC__JOB_portal = fmsRelatedRecord($UPC_row, 'JOB');
$UPC__JobUpcPdk_portal = fmsRelatedRecord($UPC_row, 'JobUpcPdk');
$UPC__JobCmpCUSTOMER_portal = fmsRelatedRecord($UPC_row, 'JobCmpCUSTOMER');
$UPC__JobUpcDie_portal = fmsRelatedRecord($UPC_row, 'JobUpcDie');
$UPC__JobCmpPRINTER_portal = fmsRelatedRecord($UPC_row, 'JobCmpPRINTER');
$UPC__JobUpcSpe_portal = fmsRelatedRecord($UPC_row, 'JobUpcSpe');
$UPC__JobSkuSkdDie_portal = fmsRelatedRecord($UPC_row, 'JobSkuSkdDie');
$UPC__JobUpcSym_portal = fmsRelatedRecord($UPC_row, 'JobUpcSym');
$UPC__jobUpcImg_portal = fmsRelatedRecord($UPC_row, 'jobUpcImg');
$UPC__JobUpcDoc_portal = fmsRelatedRecord($UPC_row, 'JobUpcDoc');


$PDK_row = current($PDK_result->getRecords());

$PDK__calc_portal = fmsRelatedRecord($PDK_row, 'calc');
$PDK__JobCmpSUPPLIER_portal = fmsRelatedRecord($PDK_row, 'JobCmpSUPPLIER');
$PDK__JobUpcSku_portal = fmsRelatedRecord($PDK_row, 'JobUpcSku');
$PDK__JobUpc_portal = fmsRelatedRecord($PDK_row, 'JobUpc');
$PDK__JobUpcPdkLci_portal = fmsRelatedRecord($PDK_row, 'JobUpcPdkLci');


// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php
// Set origin session var
$_SESSION['origin'] = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Set PDKstatus based upon the field upcPDKstatus
$PDKstatusOrig = $PDK_row->getField('JobUpc::upcPDKStatus');
$PDKstatus = $PDK_row->getField('JobUpc::upcPDKStatus');

switch ($PDKstatus) {
    case "":
        $PDKstatus = "Active";
		break;
	case "New":
        $PDKstatus = "Active";
		break;
    case "Active":
        $PDKstatus = "Active";
		break;
    case "Submitted":
        $PDKstatus = "Submitted";
		break;
	case "Complete":
        $PDKstatus = "Complete";
		break;
	case "Submitted Incomplete":
        $PDKstatus = "Submitted Incomplete";
		break;
}

// VARS REQUEST
$skuInfoReturned = "";
$skuInfoPassFail = "";

if(isset($_REQUEST['skuInfoReturned'])){
	$skuInfoReturned = $_REQUEST["skuInfoReturned"];
}
if(isset($_REQUEST['skuInfoPassFail'])){
	$skuInfoPassFail = $_REQUEST["skuInfoPassFail"];
}

// VARS FROM DB
$upc_PK = $UPC_row->getField('upc_PK');
$sku_PK = $UPC_row->getField('upc_FK_sku');
$upc_FK_job = $UPC_row->getField('upc_FK_job');
$backToDetail = $_SERVER['REQUEST_URI'];
$upc_PK = $UPC_row->getField('upc_PK');
$upc_FK_die = $UPC_row->getField('upc_FK_die');

$layoutPDK = $ContactLogin->getLayout('sup-PDK');
$pdkAllergens = $layoutPDK->getValueListTwoFields("pdkAllergens");
$pdkHealthClaims = $layoutPDK->getValueListTwoFields("pdkHealthClaims");
$pdkPackageConfiguration = $layoutPDK->getValueListTwoFields("pdkPackageConfiguration");
$yes_no = $layoutPDK->getValueListTwoFields("Yes/No");

// Save and Submit menu buttons
$btn_save = "<li><a value=\"Save PDK\" onclick=\"savePDKform()\">Save</a></li>";
$btn_submit = "<li><a value=\"Submit PDK\" onclick=\"submitPDKform()\">Submit</a></li>";

$btn_assign_printer = "<ul id=\"addBtn\" name=\"btn_assign_printer\"><li><a onclick=\"savePDKonExit('assign_printer.php?j=$upc_FK_job')\"></a></li></ul>";

$btn_assign_symbol = "<ul id=\"addBtn\" name=\"btn_assign_symbol\"><li><a onclick=\"savePDKonExit('symbols.php?j=$upc_FK_job&u=$upc_PK&backToDetail=$backToDetail')\"></a></li></ul>";

$btn_assign_spec = "<ul id=\"addBtn\" name=\"btn_assign_spec\"><li><a onclick=\"savePDKonExit('spec_assign.php?j=$upc_FK_job&u=$upc_PK&backToDetail=$backToDetail')\"></a></li></ul>";

$btn_assign_dieline = "<ul id=\"addBtn\" name=\"btn_assign_dieline\"><li><a onclick=\"savePDKonExit('dieline.php?j=$upc_FK_job&u=$upc_PK&backToDetail=$backToDetail')\"></a></li></ul>";

$btn_assign_documents = "<ul id=\"addBtn\" name=\"btn_assign_documents\"><li><a onclick=\"savePDKonExit('document.php?j=$upc_FK_job&u=$upc_PK&backToDetail=$backToDetail')\"></a></li></ul>";

$btn_assign_imagery = "<ul id=\"addBtn\" name=\"btn_assign_imagery\"><li><a onclick=\"savePDKonExit('imagery.php?j=$upc_FK_job&u=$upc_PK&backToDetail=$backToDetail')\"></a></li></ul>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | SKU: <?php echo $UPC_row->getField('JobUpcSku::skuName'); ?></title>
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
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPT -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script>

$(document).ready(function(){
	var PDKstatus = '<?php echo $PDKstatus; ?>';
	var infoReturned = '<?php echo $skuInfoReturned; ?>';
	var infoPassFail = '<?php echo $skuInfoPassFail; ?>';
	var PDKstatusOrig = '<?php echo $PDKstatusOrig; ?>';
	var statusIncompleteMsg = 'STATUS INCOMPLETE: Please fill in all required fields.';
	document.getElementById("status").value= PDKstatus;

	toggleInfoPanel(infoReturned, infoPassFail);

	if (PDKstatusOrig == "Submitted Incomplete"){
		$("#infoPanel").text(statusIncompleteMsg);
		$("#infoPanel").addClass("errorVar");
		$("#infoPanel").fadeIn();
	}

	// NEW SCRIPT - COMPLETE STATUS HIDE INPUTS, ETC.
	if (PDKstatusOrig == "Complete" || PDKstatusOrig == "Submitted"){
		var value = '';
		$(".itemDeassignRight").remove();
		$(":input[type='hidden']").remove();
		$('input, textarea, select').attr("disabled", "disabled").show();
	}

	$("#addBtn a").hover(function() {
		$(this).next("em").animate({opacity: "show", top: "-75"}, "slow");
	}, function() {
		$(this).next("em").animate({opacity: "hide", top: "-85"}, "fast");
	});

	$("#DIELINES tr, #IMAGERY tr, #SYMBOLS tr, #DOCUMENTS tr").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
    );

	$("ul#addBtn[name='btn_assign_printer']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add Printer',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	$("ul#addBtn[name='btn_assign_spec']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add New Specification',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	$("ul#addBtn[name='btn_assign_dieline']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add New Dieline',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	$("ul#addBtn[name='btn_assign_symbol']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add New Symbol',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	$("ul#addBtn[name='btn_assign_imagery']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add New Imagery',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	$("ul#addBtn[name='btn_assign_documents']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: 'Add New Document',
		style: {
			name: 'mystyle' // Inherit from preset style
		},
		position: {
		  corner: {
			 target: 'topLeft',
			 tooltip: 'bottomRight'
		  }
	   }
	});

	// pdkFlavoring
	$('tr#flavoring').hide();
	if($('input[name=pdkFlavoringRequired]:checked').val() == 'Yes'){
		$('#flavoring').show();
	} else {
		$('#flavoring').hide();
	}
	
	$('input[name=pdkFlavoringRequired]').change(function() {
	  var itemValue = $('input[name=pdkFlavoringRequired]:checked').val();
	  if(itemValue == 'Yes'){
		$('#flavoring').fadeIn();
		$('#flavoring').css('background-color', '#FFFFCC').focus();
	  } else {
		  $('input[name=pdFlavoring]').val('');
		  $('#flavoring').fadeOut();
	  }
	});
  
	// pdkCountryOfOrigin
	$('tr#country_of_origin').hide();
	if($('input[name=pdkCOOL]:checked').val() == 'Yes'){
		$('#country_of_origin').show();
	} else {
		$('#country_of_origin').hide();
	}
	
	$('input[name=pdkCOOL]').change(function() {
	  var itemValue = $('input[name=pdkCOOL]:checked').val();
	  if(itemValue == 'Yes'){
		$('#country_of_origin').fadeIn();
		$('#country_of_origin').css('background-color', '#FFFFCC').focus();
	  } else {
		  $('input[name=pdkCountryOfOrigin]').val('');
		  $('#country_of_origin').fadeOut();
	  }
	});
	
});

function submitPDKform(){
	var myForm = document.getElementById("edit_pdk");
	var SS = Spry.Widget.Form.validate(myForm);
	var upc_FK_die = '<?php echo $UPC__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX'); ?>';
	var upc_FK_spe = '<?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX'); ?>';
	var assigned_printers = '<?php echo $UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK'); ?>';
	var cool = $('input[name=pdkCOOL]:checked').val();
  var country_of_origin = $('input[name=pdkCountryOfOrigin]').val();
  var flavoring_req = $('input[name=pdkFlavoringRequired]:checked').val();
  var flavoring = $('input[name=pdkFlavoring]').val();

	if(assigned_printers){
		$("#PRINTERS td[name='printerHeader']").removeClass("table_headerReq");
		$("#PRINTERS td[name='printerHeader']").addClass("table_header");
	} else {
		SS = false;
		$("#PRINTERS td[name='printerHeader']").removeClass("table_header");
		$("#PRINTERS td[name='printerHeader']").addClass("table_headerReq");
		$("#PRINTERS td[name='noSpecInstr']").removeClass("table_row_instr");
		$("#PRINTERS td[name='noSpecInstr']").addClass("table_row_instr_req");
	}

	if(upc_FK_spe != ''){
		$("#PRINTER_SPECS td[name='printerSpecHeader']").removeClass("table_headerReq");
		$("#PRINTER_SPECS td[name='printerSpecHeader']").addClass("table_header");
	} else {
		SS = false;
		$("#PRINTER_SPECS td[name='printerSpecHeader']").removeClass("table_header");
		$("#PRINTER_SPECS td[name='printerSpecHeader']").addClass("table_headerReq");
		$("#PRINTERS td[name='noSpecInstr']").removeClass("table_row_instr");
		$("#PRINTERS td[name='noSpecInstr']").addClass("table_row_instr_req");
	}

	if(upc_FK_die != ''){
		$("#DIELINES td[name='dielineHeader']").removeClass("table_headerReq");
		$("#DIELINES td[name='dielineHeader']").addClass("table_header");
	} else {
		SS = false;
		$("#DIELINES td[name='dielineHeader']").removeClass("table_header");
		$("#DIELINES td[name='dielineHeader']").addClass("table_headerReq");
		$("#DIELINES td[name='noDieInstr']").removeClass("table_row_instr");
		$("#DIELINES td[name='noDieInstr']").addClass("table_row_instr_req");
	}

	if(!$("#pdkPackageConfigurationTD input:checked").val()){
		SS = false;
		$("#pdkPackageConfigurationTD").css('background-color', '#FF99A8');
		$("#pdkPackageConfigurationLabel").css('background-color', '#FF99A8');
	} else {
		$("#pdkPackageConfigurationTD").css('background-color', '#FFF');
		$("#pdkPackageConfigurationLabel").css('background-color', '#EFEFEF');
	}

	if(!$("#pdkHealthClaimsTD input:checked").val()){
		SS = false;
		$("#pdkHealthClaimsTD").css('background-color', '#FF99A8');
		$("#pdkHealthClaimsLabel").css('background-color', '#FF99A8');
	} else {
		$("#pdkHealthClaimsTD").css('background-color', '#FFF');
		$("#pdkHealthClaimsLabel").css('background-color', '#EFEFEF');
	}

	if(!$("#pdkAllergensInProductTD input:checked").val()){
		SS = "false";
		$("#pdkAllergensInProductTD").css('background-color', '#FF99A8');
		$("#pdkAllergensInProductLabel").css('background-color', '#FF99A8');
	} else {
		$("#pdkAllergensInProductTD").css('background-color', '#FFF');
		$("#pdkAllergensInProductLabel").css('background-color', '#EFEFEF');
	}

	if(!$("#pdkAllergensInFacilityTD input:checked").val()){
		SS = false;
		$("#pdkAllergensInFacilityTD").css('background-color', '#FF99A8');
		$("#pdkAllergensInFacilityLabel").css('background-color', '#FF99A8');
	} else {
		$("#pdkAllergensInFacilityTD").css('background-color', '#FFF');
		$("#pdkAllergensInFacilityLabel").css('background-color', '#EFEFEF');
	}

	if(!$("#upcExistingImageryTD input:checked").val()){
		SS = false;
		$("#upcExistingImageryLabel").removeClass("table_row");
		$("#upcExistingImageryTD").removeClass("table_row");
		$("#upcExistingImageryLabel").addClass("required_jquery");
		$("#upcExistingImageryTD").addClass("required_jquery");
	} else {
		$("#upcExistingImageryLabel").removeClass("required_jquery");
		$("#upcExistingImageryTD").removeClass("required_jquery");
		$("#upcExistingImageryLabel").addClass("table_row");
		$("#upcExistingImageryTD").addClass("table_row");
	}
  
	// COOL REQUIREMENT
	if(!cool){
		SS = false;
		$("#pdkCOOL").children("td").css('background-color', '#FF99A8');
	} else {
		$("#pdkCOOL td:first").css('background-color', '#EFEFEF');
		$("#pdkCOOL td:last").css('background-color', '#FFF');
	}
	
	// COOL & COO REQUIREMENT
	if(cool == 'Yes' && !country_of_origin){
		SS = false;
		$("#pdkCountryOfOriginTD").css('background-color', '#FF99A8');
		$("#pdkCountryOfOriginLabel").css('background-color', '#FF99A8');
		//alert('fail - ' + 'COOL: ' + cool + ' | COO: ' + country_of_origin);
	} else {
		$("#pdkCountryOfOriginTD").css('background-color', '#FFF');
		$("#pdkCountryOfOriginLabel").css('background-color', '#EFEFEF');
		//alert('pass - ' + 'COOL: ' + cool + ' | COO: ' + country_of_origin);
	}
  
  // FLAVORING REQUIREMENT
	if(!flavoring_req){
		SS = false;
		$("#pdkFlavoringRequired").children("td").css('background-color', '#FF99A8');
	} else {
		$("#pdkFlavoringRequired td:first").css('background-color', '#EFEFEF');
		$("#pdkFlavoringRequired td:last").css('background-color', '#FFF');
	}
	
	// FLAVORING & FLAVORING REQUIREMENT
	if(flavoring_req == 'Yes' && !flavoring){
		SS = false;
		$("#pdkFlavoringTD").css('background-color', '#FF99A8');
		$("#pdkFlavoringLabel").css('background-color', '#FF99A8');
		//alert('fail - ' + 'flavoring_req: ' + flavoring_req + ' | flavoring: ' + flavoring);
	} else {
		$("#pdkFlavoringTD").css('background-color', '#FFF');
		$("#pdkFlavoringLabel").css('background-color', '#EFEFEF');
	  //alert('pass - ' + 'flavoring_req: ' + flavoring_req + ' | flavoring: ' + flavoring);
	}

	if(SS == true){
		$("#status").val("Submitted");
		$("#edit_pdk").submit();
	} else {
		flashNotice("NOTICE: There were issues saving this SKU", "Please enter valid data into the fields highlighted in red");
		highlightErrors();
	}
}

function savePDKform(){
	$("#edit_pdk").submit();
}

function savePDKonExit(url){
	$("#redirect").val(url);
	$("#edit_pdk").submit();
}

function viewItem(url){
	window.open(url,'Download');
}

// DEASSIGN
function deassignItem(id, itemType, itemName){
	$("#deassign_item").val(id);
	$("#deassign_field").val(itemType);
	$("#deassign_name").val(itemName);
	$("#deassign_form").submit();
	alert(id + " | " + itemType + " | " + itemName);
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
    <h1>SKU Detail</h1>
  </div><!-- end #header -->

  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

  <? if($PDKstatus != "Complete" && $PDKstatus != "Submitted"){ ?>
	<h1>Add New</h1>
	<ul id="add_new" class="MenuBarVertical">
		<li><a onclick="savePDKonExit('assign_printer.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>')">Printer</a></li>
		<?php if($UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>
			<li><a onclick="savePDKonExit('spec_assign.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Printer Specification</a></li>
		<? } ?>
		  <li><a onclick="savePDKonExit('dieline.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Dieline</a></li>
		  <li><a onclick="savePDKonExit('document.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Document</a></li>
		  <li><a onclick="savePDKonExit('imagery.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Imagery</a></li>
		  <li><a onclick="savePDKonExit('symbols.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Symbol</a></li>
	</ul>
	<? } ?>

<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">

<?php
	// SAVE BUTTON
	if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
		echo $btn_save;
	}
?>
	<li><a onclick="savePDKonExit('pdk_duplication.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>&u=<?php echo $UPC_row->getField('upc_PK'); ?>&backToDetail=<?php echo $_SERVER['REQUEST_URI']; ?>')">Duplicate SKU Info</a></li>
<?php
	// SUBMIT BUTTON
	if ($PDKstatusOrig <> "Complete" && $PDKstatusOrig <> "Submitted") {
		echo $btn_submit;
	}
?>
	<li><a href="job.php?j=<?php echo $UPC_row->getField('upc_FK_job'); ?>">Back to Job</a></li>
</ul>
<!-- end #sidebar1 --></div>

<!-- MAIN CONTENT -->
  <div id="mainContent">

<!-- INFO PANEL -->
	<div id="infoPanel" class=""></div>

<h1><?php echo $UPC_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> | <?php echo $UPC_row->getField('JOB::jobNameWbnd'); ?> | <?php echo $UPC_row->getField('JOB::jobNumber'); ?></h1>
<p>Please enter item information in the fields below. Select <em>Save</em> to if you would like to come back and complete/submit the form at a later time. Select <em>Submit</em> once the information is complete.</p>

<!-- SKU INFO -->
    <table id="SKU" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">sku</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Name</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('JobUpcSku::skuName'); ?>&nbsp;</td>
        <td class="table_side_head_wrap"> Status</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('upcPDKStatus'); ?>&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Size</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('JobUpcSku::skuSize'); ?>&nbsp;</td>
        <td class="table_side_head_wrap"> Type</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('JobUpcSku::skuPDKType'); ?>&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">UPC</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('upcCode'); ?>&nbsp;</td>
        <td class="table_side_head_wrap">Date Completed</td>
        <td class="table_row" width="35%"><?php echo $UPC_row->getField('upcDateComplete'); ?>&nbsp;</td>
      </tr>
    </table>

<!-- PRINTERS -->
    To add printer information, first click the + button in the PRINTERS ASSIGNED field to add the printer contact, then the + button in the PRINTER SPECS field to add printer specifications.
    <table id="PRINTERS" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	  	<td name="printerHeader" class="table_header" colspan="4">Print Info</td>
	  </tr>
	  <tr>
        <td class="printInfoSub" colspan="4">Printers Assigned
          <div id="tooltip"></div>
          <!-- ADD BTN -->
		  <?php
			// ASSIGN PRINTER BUTTON
			if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
				echo $btn_assign_printer;
			} ?>
        </td>
      </tr>

      <?php if(!$UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="4">Please assign a Printer to this Job, then you may assign Printer Specifications.</td>
      </tr>
      <? } ?>

<!-- PRINTER SPECS -->
      <?php
$UPC__JobCmpPRINTER_portal_rownum = 1;
foreach(fmsRelatedSet($UPC_row,'JobCmpPRINTER') as $UPC__JobCmpPRINTER_portal_row=>$UPC__JobCmpPRINTER_portal){
?>
        <tr>
          <td class="table_row" colspan="4"><?php echo $UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmpCompany'); ?></td>
        </tr>
    <?php if($UPC__JobCmpPRINTER_portal_rownum == 0) break; else $UPC__JobCmpPRINTER_portal_rownum++;
}//portal_end ?>

		<?php if($UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>

		<tr>
			<td name="printerSpecHeader" class="printInfoSub" width="20%">Printer Specifications</td>
			<td name="printerSpecHeader" class="printInfoSub">&nbsp;</td>
			<td name="printerSpecHeader" class="printInfoSub" width="20%">&nbsp;</td>
		  <td name="printerSpecHeader" class="printInfoSub" align="RIGHT" width="20%">
			<!-- ADD BTN -->
			<?php
			// ASSIGN PRINTER SPEC BUTTON
			if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
				echo $btn_assign_spec;
			} ?>
		  </td>
      </tr>

      <?php if(!$UPC__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX')){ ?>
      <tr>
      	<td name="noSpecInstr" class="table_row_instr" colspan="4">No Printer Specifications  currently assigned to this SKU.</td>
      </tr>
      <? } ?>

      <?php
$UPC__JobUpcSpe_portal_rownum = 1;
foreach(fmsRelatedSet($UPC_row,'JobUpcSpe') as $UPC__JobUpcSpe_portal_row=>$UPC__JobUpcSpe_portal){

		$itemName = $UPC__JobUpcSpe_portal->getField('JobUpcSpe::speName');
		$itemID = $UPC__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX');

?>
        <tr>
          <td class="table_row_borderR" width="20%"><?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?></td>
          <td class="table_row_borderR"><?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpe::speName'); ?></td>
          <td class="table_row_borderR" width="20%"><?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpeConPRINTER::conNameFull'); ?></td>
          <td class="table_row" width="20%">
		  	<div class="itemDeassignRight" title="Deassign Printer Spec" onclick="deassignItem('<?php echo $itemID;?>', 'spec', '<?php echo $itemName;?>')"></div>
			<?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpeConPRINTER::conPhoneFull'); ?>
		  </td>
        </tr>
    <?php if($UPC__JobUpcSpe_portal_rownum == 0) break; else $UPC__JobUpcSpe_portal_rownum++;
}//portal_end ?>

		<?php } ?>
    </table>

<!-- DIELINES -->
    To add dielines, click on the + button in the DIELINES field. This will give you the option of uploading a new dieline, or choosing from a list of dielines associated with previous jobs.
    <table id="DIELINES" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td name="dielineHeader" class="table_header">Dielines</td>
        <td name="dielineHeader" class="table_header" align="RIGHT">
			<!-- ADD BTN -->
			<?php
            // ASSIGN DIELINE BUTTON
            if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
                echo $btn_assign_dieline;
            } ?>
        </td>
      </tr>

      <?php if(!$UPC__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX')){ ?>
      <tr>
      	<td name="noDieInstr" class="table_row_instr" colspan="2">No Dielines currently assigned to this SKU.</td>
      </tr>
      <? } ?>

      <?php
		$UPC__JobUpcDie_portal_rownum = 1;
		foreach(fmsRelatedSet($UPC_row,'JobUpcDie') as $UPC__JobUpcDie_portal_row=>$UPC__JobUpcDie_portal){

			$itemName = $UPC__JobUpcDie_portal->getField('JobUpcDie::DieFileName');
			$itemID = $UPC__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX');

		?>
        <tr id="item">
          <td class="table_row" colspan="2">
			<div id="itemViewTable" onclick="viewItem('<?php echo $UPC__JobUpcDie_portal->getField('JobUpcDie::DiePath'); ?>')" title="View Dieline"></div>
			<div class="itemDeassignRight" title="Deassign dieline" onclick="deassignItem('<?php echo $itemID;?>', 'dieline', '<?php echo $itemName;?>')"></div>
			<div><?php echo $UPC__JobUpcDie_portal->getField('JobUpcDie::DieFileName'); ?></div>
		  </td>
        </tr>
        <?php if($UPC__JobUpcDie_portal_rownum == 0) break; else $UPC__JobUpcDie_portal_rownum++;
		}//portal_end ?>
    </table>

<!-- SKU INFO TABLE -->
    <form action="sku_edit_response.php" method="post" enctype="multipart/form-data" name="edit_pdk" id="edit_pdk">
    <table id="SKU_info" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Item Information: <?php echo $PDK_row->getField('JobUpcSku::skuPDKType'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Product Name<br /></td>
        <td class="table_row">
          <span id="sprytextfield1">
            <input name="pdkProductName" type="text" value="<?php echo $PDK_row->getField('pdkProductName'); ?>" id="pdkProductName">
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Net Weight / Size &amp; Count</td>
        <td class="table_row">
          <span id="sprytextfield2">
            <input name="pdkNetWeight" type="text" value="<?php echo $PDK_row->getField('pdkNetWeight'); ?>" id="pdkNetWeight">
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Secondary Descriptors</td>
        <td class="table_row">
          <span id="sprytextarea1">
            <textarea name="pdkSecondaryDescriptors" id="pdkSecondaryDescriptors" cols="45" rows="5"><?php echo $PDK_row->getField('pdkSecondaryDescriptors'); ?></textarea>
          </span>
        </td>
      </tr>


	  <!-- COOL -->
      <tr id="pdkCOOL">
        <td class="table_side_head_req">If this product is distributed in the USA, is origin statement required under COOL?</td>
        <td class="table_row">
				<?php
					foreach ($yes_no as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkCOOL')) ) {
						echo "<input name=\"pdkCOOL\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
					  } else {
						echo "<input name=\"pdkCOOL\" type=\"radio\" value=\"{$value}\">{$value}\n";
					  }
					}
				?>
				</td>
	    </tr>

	  <!-- COUNTRY OF ORIGIN -->
	  	<tr id="country_of_origin">
        <td class="table_side_head_req" id="pdkCountryOfOriginLabel">Country of Origin</td>
        <td class="table_row" id="pdkCountryOfOriginTD">
          <input name="pdkCountryOfOrigin" type="text" value="<?php echo $PDK_row->getField('pdkCountryOfOrigin'); ?>" id="pdkCountryOfOrigin">
        </td>
      </tr>

	  <!-- REQUIRED PRODUCT CLAIMS/FEATURES/QUALIFIERS -->
      <tr>
        <td class="table_side_head_req">Required Product Claims / Features / Qualifiers</td>
        <td class="table_row">
          <span id="sprytextarea2">
            <textarea name="pdkProductClaims" id="pdkProductClaims" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductClaims'); ?></textarea>
          </span>
        </td>
      </tr>
      
      <!-- FLAVORING REQUIRED -->
      <tr id="pdkFlavoringRequired">
        <td class="table_side_head">Flavoring / Natural Artificial Natural & Artificial Required?</td>
        <td class="table_row">
				<?php
					foreach ($yes_no as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkFlavoringRequired')) ) {
						echo "<input name=\"pdkFlavoringRequired\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
					  } else {
						echo "<input name=\"pdkFlavoringRequired\" type=\"radio\" value=\"{$value}\">{$value}\n";
					  }
					}
				?>
				</td>
	    </tr>
      
	    <!-- FLAVORING -->
	  	<tr id="flavoring">
        <td class="table_side_head_req" id="pdkFlavoringLabel">Flavoring</td>
        <td class="table_row" id="pdkFlavoringTD">
          <input name="pdkFlavoring" type="text" value="<?php echo $PDK_row->getField('pdkFlavoring'); ?>" id="pdkFlavoring">
        </td>
      </tr>

	  <!-- HEALTH CLAIMS -->
      <tr>
        <td class="table_side_head_req" id="pdkHealthClaimsLabel">Health Claims</td>
        <td class="table_row" id="pdkHealthClaimsTD">
				<?php
					foreach ($pdkHealthClaims as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkHealthClaims')) ) {
						echo "<input name=\"pdkHealthClaims[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\" checked=\"checked\">{$value}";
					  } else {
						echo "<input name=\"pdkHealthClaims[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\">{$value}";
					  }
					}
				?>
				</td>
      </tr>

	  <!-- PRODUCT CLAIMS OPTIONAL -->
      <tr>
        <td class="table_side_head">Product Claims Optional</td>
        <td class="table_row">
        	<span id="sprytextarea10">
          	<textarea name="pdkProductClaimsOptional" id="pdkProductClaimsOptional" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductClaimsOptional'); ?></textarea>
					</span>
				</td>
      </tr>

	  <!-- INGREDIENT CLAIMS -->
      <tr>
        <td class="table_side_head_req">Ingredient Statement</td>
        <td class="table_row">
          <span id="sprytextarea3">
            <textarea name="pdkIngredientStatement" id="pdkIngredientStatement" cols="45" rows="5"><?php echo $PDK_row->getField('pdkIngredientStatement'); ?></textarea>
          </span>
        </td>
      </tr>

	  <!-- PACKAGE COMPONENTS -->
      <tr>
        <td class="table_side_head_wrap">Package Components:<br>Cap/Lid Colors etc.</td>
        <td class="table_row">
          <span id="sprytextfield4">
            <input name="pdkPackageComponents" type="text" value="<?php echo $PDK_row->getField('pdkPackageComponents'); ?>" id="pdkPackageComponents">
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req" id="pdkPackageConfigurationLabel">Package Configuration</td>
        <td class="table_row" id="pdkPackageConfigurationTD">
				<?php
					foreach ($pdkPackageConfiguration as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkPackageConfiguration')) ) {
						echo "<input name=\"pdkPackageConfiguration[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\" checked=\"checked\">{$value}";
					  } else {
						echo "<input name=\"pdkPackageConfiguration[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\">{$value}";
					  }
					}
				?>
				</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Product Preparation Instructions and/or Storage Instructions</td>
        <td class="table_row">
        	<span id="sprytextarea4">
	          <textarea name="pdkProductPreparationInstructions" id="pdkProductPreparationInstructions" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductPreparationInstructions'); ?></textarea>
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Recipe and/or Romance Copy</td>
        <td class="table_row">
        	<span id="sprytextarea5">
          	<textarea name="pdkRecipe" id="pdkRecipe" cols="45" rows="5"><?php echo $PDK_row->getField('pdkRecipe'); ?></textarea>
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Warnings/Caution Statements – Front Panel</td>
        <td class="table_row">
	        <span id="sprytextarea6">
	          <textarea name="pdkWarningStatements" id="pdkWarningStatements" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatements'); ?></textarea>
          </span>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Warnings/Caution Statements – Back/Side Panel</td>
        <td class="table_row">
        	<span id="sprytextarea9">
	          <textarea name="pdkWarningStatementsBackSidePanel" id="pdkWarningStatementsBackSidePanel" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatementsBackSidePanel'); ?></textarea>
					</span>
				</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">USDA Establishment Number</td>
        <td class="table_row">
        	<span id="sprytextfield81">
          	<input name="pdkUsdaNum" type="text" value="<?php echo $PDK_row->getField('pdkUsdaNum'); ?>" id="pdkUsdaNum">
					</span>
				</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Certified 3rd Party</td>
        <td class="table_row">
        	<span id="sprytextfield82">
          	<input name="pdkProductCertified3rdParty" type="text" value="<?php echo $PDK_row->getField('pdkProductCertified3rdParty'); ?>" id="pdkProductCertified3rdParty">
					</span>
				</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Does this product use sustainable packaging?</td>
        <td class="table_row">
				<?php
					foreach ($yes_no as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkProductSustainablePackaging')) ) {
						echo "<input name=\"pdkProductSustainablePackaging\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
					  } else {
						echo "<input name=\"pdkProductSustainablePackaging\" type=\"radio\" value=\"{$value}\">{$value}\n";
					  }
					}
				?>
		  	</td>
      </tr>
    </table>

    <!-- NUTRITION INFO -->
    <table id="nutrition_info" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="5">Nutrition  Information (NLEA)</td>
      </tr>

      <!-- NET WEIGHT -->
      <tr>
        <td class="table_sub_head">Net Weight (per item)</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>

      <!-- SERVER SIZE -->
      <tr>
        <td class="table_side_head_req">Serving Size</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield5">
            <input name="pdkServingSizeAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkServingSizeAsPackaged'); ?>" id="pdkServingSizeAsPackaged">
          </span>
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield6">
            <input name="pdkServingSizeAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkServingSizeAsPrepared'); ?>" id="pdkServingSizeAsPrepared">
          </span>
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- SERVINGS PER CONTAINER -->
      <tr>
        <td class="table_side_head_req">Servings per Container</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield7">
            <input name="pdkServingsPerContainerAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkServingsPerContainerAsPackaged'); ?>" id="pdkServingsPerContainerAsPackaged">
          </span>
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield8">
            <input name="pdkServingsPerContainerAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkServingsPerContainerAsPrepared'); ?>" id="pdkServingsPerContainerAsPrepared">
          </span>
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- AMOUNT PER SERVING -->
      <tr>
        <td class="table_sub_head">Amount per Serving</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>

      <!-- CALORIES -->
      <tr>
        <td class="table_side_head_req">Calories</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield9">
            <input name="pdkCaloriesAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesAsPackaged'); ?>" id="pdkCaloriesAsPackaged">
         </span>
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield10">
            <input name="pdkCaloriesAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesAsPrepared'); ?>" id="pdkCaloriesAsPrepared">
          </span>
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- CALORIES FROM FAT -->
      <tr>
        <td class="table_side_head_req">Calories from Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield11">
            <input name="pdkCaloriesFromFatAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesFromFatAsPackaged'); ?>" id="pdkCaloriesFromFatAsPackaged">
          </span>
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield12">
            <input name="pdkCaloriesFromFatAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesFromFatAsPrepared'); ?>" id="pdkCaloriesFromFatAsPrepared">
          </span>
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- TOTAL FAT -->
      <tr>
        <td class="table_side_head_req">Total Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield13">
            <input name="pdkTotalFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPackagedG'); ?>" id="pdkTotalFatAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield14">
            <input name="pdkTotalFatAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPackagedPerc'); ?>" id="pdkTotalFatAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield15">
            <input name="pdkTotalFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPreparedG'); ?>" id="pdkTotalFatAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield16">
            <input name="pdkTotalFatAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPreparedPerc'); ?>" id="pdkTotalFatAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- SATURATED FAT -->
      <tr>
        <td class="table_side_head_req">Saturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield17">
            <input name="pdkSaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPackagedG'); ?>" id="pdkSaturatedFatAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield18">
            <input name="pdkSaturatedFatAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPackagedPerc'); ?>" id="pdkSaturatedFatAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield19">
            <input name="pdkSaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPreparedG'); ?>" id="pdkSaturatedFatAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield20">
            <input name="pdkSaturatedFatAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPreparedPerc'); ?>" id="pdkSaturatedFatAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- TRANS FAT -->
      <tr>
        <td class="table_side_head_req">Trans Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield21">
            <input name="pdkTransFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTransFatAsPackagedG'); ?>" id="pdkTransFatAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield22">
            <input name="pdkTransFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTransFatAsPreparedG'); ?>" id="pdkTransFatAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- POLUUNSATURATED FAT -->
      <tr>
        <td class="table_side_head_req">Polyunsaturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield23">
            <input name="pdkPolyunsaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkPolyunsaturatedFatAsPackagedG'); ?>" id="pdkPolyunsaturatedFatAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield24">
            <input name="pdkPolyunsaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkPolyunsaturatedFatAsPreparedG'); ?>" id="pdkPolyunsaturatedFatAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- MONOUNSATURATED FAT -->
      <tr>
        <td class="table_side_head_req">Monounsaturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield25">
            <input name="pdkMonounsaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkMonounsaturatedFatAsPackagedG'); ?>" id="pdkMonounsaturatedFatAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield26">
            <input name="pdkMonounsaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkMonounsaturatedFatAsPreparedG'); ?>" id="pdkMonounsaturatedFatAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- CHOLESTEROL -->
      <tr>
        <td class="table_side_head_req">Cholesterol</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield27">
            <input name="pdkCholesterolAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPackagedMG'); ?>" id="pdkCholesterolAsPackagedMG">
          </span>mg
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield28">
            <input name="pdkCholesterolAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPackagedPerc'); ?>" id="pdkCholesterolAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield29">
            <input name="pdkCholesterolAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPreparedMG'); ?>" id="pdkCholesterolAsPreparedMG">
          </span>mg
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield30">
            <input name="pdkCholesterolAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPreparedPerc'); ?>" id="pdkCholesterolAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- SODIUM --> 
      <tr>
        <td class="table_side_head_req">Sodium</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield31">
            <input name="pdkSodiumAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPackagedMG'); ?>" id="pdkSodiumAsPackagedMG">
          </span>mg
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield32">
            <input name="pdkSodiumAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPackagedPerc'); ?>" id="pdkSodiumAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield33">
            <input name="pdkSodiumAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPreparedMG'); ?>" id="pdkSodiumAsPreparedMG">
          </span>mg
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield34">
            <input name="pdkSodiumAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPreparedPerc'); ?>" id="pdkSodiumAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- TOTAL CARBOHYDRATE -->
      <tr>
        <td class="table_side_head_req">Total Carbohydrate</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield39">
            <input name="pdkTotalCarbohydrateAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPackagedG'); ?>" id="pdkTotalCarbohydrateAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield40">
            <input name="pdkTotalCarbohydrateAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPackagedPerc'); ?>" id="pdkTotalCarbohydrateAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield41">
            <input name="pdkTotalCarbohydrateAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPreparedG'); ?>" id="pdkTotalCarbohydrateAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield42">
            <input name="pdkTotalCarbohydrateAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPreparedPerc'); ?>" id="pdkTotalCarbohydrateAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- DIETARY FIBER -->
      <tr>
        <td class="table_side_head_req">Dietary Fiber</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield43">
            <input name="pdkDietaryFiberAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPackagedG'); ?>" id="pdkDietaryFiberAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield44">
            <input name="pdkDietaryFiberAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPackagedPerc'); ?>" id="pdkDietaryFiberAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield45">
            <input name="pdkDietaryFiberAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPreparedG'); ?>" id="pdkDietaryFiberAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield46">
            <input name="pdkDietaryFiberAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPreparedPerc'); ?>" id="pdkDietaryFiberAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- TOTAL SUGARS -->
      <tr>
        <td class="table_side_head_req">Total Sugars</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield47">
            <input name="pdkSugarsAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAsPackagedG'); ?>" id="pdkSugarsAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield48">
            <input name="pdkSugarsAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAsPreparedG'); ?>" id="pdkSugarsAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>

      <!-- ADDED SUGARS -->
      <tr>
        <td class="table_side_head_req">Added Sugars</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield49">
            <input name="pdkSugarsAddedAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAddedAsPackagedG'); ?>" id="pdkSugarsAddedAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield83">
            <input name="pdkSugarsAddedAsPackagedPer" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAddedAsPackagedPer'); ?>" id="pdkSugarsAddedAsPackagedPer">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield50">
            <input name="pdkSugarsAddedAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAddedAsPreparedG'); ?>" id="pdkSugarsAddedAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield84">
            <input name="pdkSugarsAddedAsPreparedPer" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAddedAsPreparedPer'); ?>" id="pdkSugarsAddedAsPreparedPer">
          </span>%
        </td>
      </tr>

      <!-- PROTEIN -->
      <tr>
        <td class="table_side_head_req">Protein</td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield49">
            <input name="pdkProteinAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPackagedG'); ?>" id="pdkProteinAsPackagedG">
          </span>g
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield83">
            <input name="pdkProteinAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPackagedPerc'); ?>" id="pdkProteinAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row_borderR" width="20%">
          <span id="sprytextfield50">
            <input name="pdkProteinAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPreparedG'); ?>" id="pdkProteinAsPreparedG">
          </span>g
        </td>
        <td class="table_row" width="20%">
          <span id="sprytextfield84">
            <input name="pdkProteinAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPreparedPerc'); ?>" id="pdkProteinAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- VITAMIN INFORMATION -->
      <tr>
        <td class="table_sub_head">Vitamin Infomation</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>

      <!-- VITAMIN A -->
      <tr>
        <td class="table_side_head_req">Vitamin A</td>
        <td class="table_row">
          <span id="sprytextfield51">
            <input name="pdkVitaminAAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminAAsPackaged'); ?>" id="pdkVitaminAAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield52">
            <input name="pdkVitaminAAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminAAsPrepared'); ?>" id="pdkVitaminAAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- VITAMIN C -->
      <tr>
        <td class="table_side_head_req">Vitamin C</td>
        <td class="table_row">
          <span id="sprytextfield53">
            <input name="pdkVitaminCAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminCAsPackaged'); ?>" id="pdkVitaminCAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield54">
            <input name="pdkVitaminCAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminCAsPrepared'); ?>" id="pdkVitaminCAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- CALCIUM -->
      <tr>
        <td class="table_side_head_req">Calcium</td>
        <td class="table_row">
          <span id="sprytextfield55">
            <input name="pdkCalciumAsPackagedPer" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPackagedPer'); ?>" id="pdkCalciumAsPackagedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield85">
            <input name="pdkCalciumAsPackagedMg" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPackagedMg'); ?>" id="pdkCalciumAsPackagedMg">
          </span>mg
        </td>
        <td class="table_row">
          <span id="sprytextfield56">
            <input name="pdkCalciumAsPreparedPer" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPreparedPer'); ?>" id="pdkCalciumAsPreparedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield86">
            <input name="pdkCalciumAsPreparedMg" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPreparedMg'); ?>" id="pdkCalciumAsPreparedMg">
          </span>mg
        </td>
      </tr>

      <!-- IRON -->
      <tr>
        <td class="table_side_head_req">Iron</td>
        <td class="table_row">
          <span id="sprytextfield57">
            <input name="pdkIronAsPackagedPer" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPackagedPer'); ?>" id="pdkIronAsPackagedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield87">
            <input name="pdkIronAsPackagedMg" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPackagedMg'); ?>" id="pdkIronAsPackagedMg">
          </span>mg
        </td>
        <td class="table_row">
          <span id="sprytextfield58">
            <input name="pdkIronAsPreparedPer" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPreparedPer'); ?>" id="pdkIronAsPreparedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield88">
            <input name="pdkIronAsPreparedMg" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPreparedMg'); ?>" id="pdkIronAsPreparedMg">
          </span>mg
        </td>
      </tr>

      <!-- POTASSIUM -->
      <tr>
        <td class="table_side_head_req">Potassium</td>
        <td class="table_row">
          <span id="sprytextfield35">
            <input name="pdkPotassiumAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPackagedMG'); ?>" id="pdkPotassiumAsPackagedMG">
          </span>mg
        </td>
        <td class="table_row">
          <span id="sprytextfield36">
            <input name="pdkPotassiumAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPackagedPerc'); ?>" id="pdkPotassiumAsPackagedPerc">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield37">
            <input name="pdkPotassiumAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPreparedMG'); ?>" id="pdkPotassiumAsPreparedMG">
          </span>mg
        </td>
        <td class="table_row">
          <span id="sprytextfield38">
            <input name="pdkPotassiumAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPreparedPerc'); ?>" id="pdkPotassiumAsPreparedPerc">
          </span>%
        </td>
      </tr>

      <!-- VITAMIN D -->
      <tr>
        <td class="table_side_head">Vitamin D</td>
        <td class="table_row">
          <span id="sprytextfield59">
            <input name="pdkVitaminDAsPackagedPer" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPackagedPer'); ?>" id="pdkVitaminDAsPackagedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield89">
            <input name="pdkVitaminDAsPackagedMg" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPackagedMg'); ?>" id="pdkVitaminDAsPackagedMg">
          </span>mg
        </td>
        <td class="table_side_head noHighlight">Vitamin D</td>
        <td class="table_row">
          <span id="sprytextfield60">
            <input name="pdkVitaminDAsPreparedPer" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPreparedPer'); ?>" id="pdkVitaminDAsPreparedPer">
          </span>%
        </td>
        <td class="table_row">
          <span id="sprytextfield90">
            <input name="pdkVitaminDAsPreparedMg" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPreparedMg'); ?>" id="pdkVitaminDAsPreparedMg">
          </span>mg
        </td>
      </tr>

      <!-- VITAMIN E -->
      <tr>
        <td class="table_side_head_wrap">Vitamin E</td>
        <td class="table_row">
          <span id="sprytextfield79">
            <input name="pdkVitaminEAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminEAsPackaged'); ?>" id="pdkVitaminEAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield80">
            <input name="pdkVitaminEAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminEAsPrepared'); ?>" id="pdkVitaminEAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- THIAMIN -->
      <tr>
        <td class="table_side_head_wrap">Thiamin</td>
        <td class="table_row">
          <span id="sprytextfield61">
            <input name="pdkThiaminAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkThiaminAsPackaged'); ?>" id="pdkThiaminAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield62">
            <input name="pdkThiaminAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkThiaminAsPrepared'); ?>" id="pdkThiaminAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- RIBOFLAVIN -->
      <tr>
        <td class="table_side_head_wrap">Riboflavin</td>
        <td class="table_row">
          <span id="sprytextfield63">
            <input name="pdkRiboflavinAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkRiboflavinAsPackaged'); ?>" id="pdkRiboflavinAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield64">
            <input name="pdkRiboflavinAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkRiboflavinAsPrepared'); ?>" id="pdkRiboflavinAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- NIACIN -->
      <tr>
        <td class="table_side_head_wrap">Niacin</td>
        <td class="table_row">
          <span id="sprytextfield65">
            <input name="pdkNiacinAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkNiacinAsPackaged'); ?>" id="pdkNiacinAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield66">
            <input name="pdkNiacinAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkNiacinAsPrepared'); ?>" id="pdkNiacinAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- VITAMIN B6 -->
      <tr>
        <td class="table_side_head_wrap">Vitamin B6</td>
        <td class="table_row">
          <span id="sprytextfield71">
            <input name="pdkVitaminB6AsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB6AsPackaged'); ?>" id="pdkVitaminB6AsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield72">
            <input name="pdkVitaminB6AsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB6AsPrepared'); ?>" id="pdkVitaminB6AsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- FOLIC ACID -->
      <tr>
        <td class="table_side_head_wrap">Folic Acid</td>
        <td class="table_row">
          <span id="sprytextfield69">
            <input name="pdkFolicAcidAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkFolicAcidAsPackaged'); ?>" id="pdkFolicAcidAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield70">
            <input name="pdkFolicAcidAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkFolicAcidAsPrepared'); ?>" id="pdkFolicAcidAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- VITAMIN B12 -->
      <tr>
        <td class="table_side_head_wrap">Vitamin B12</td>
        <td class="table_row">
          <span id="sprytextfield67">
            <input name="pdkVitaminB12AsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB12AsPackaged'); ?>" id="pdkVitaminB12AsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield68">
            <input name="pdkVitaminB12AsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB12AsPrepared'); ?>" id="pdkVitaminB12AsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- PHOSPHORUS -->
      <tr>
        <td class="table_side_head_wrap">Phosphorus</td>
        <td class="table_row">
          <span id="sprytextfield73">
            <input name="pdkPhosphorusAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkPhosphorusAsPackaged'); ?>" id="pdkPhosphorusAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield74">
            <input name="pdkPhosphorusAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkPhosphorusAsPrepared'); ?>" id="pdkPhosphorusAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- MAGNESIUM -->
      <tr>
        <td class="table_side_head_wrap">Magnesium</td>
        <td class="table_row"><span id="sprytextfield75">
          <input name="pdkMagnesiumAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkMagnesiumAsPackaged'); ?>" id="pdkMagnesiumAsPackaged">
          </span>%</td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield76">
            <input name="pdkMagnesiumAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkMagnesiumAsPrepared'); ?>" id="pdkMagnesiumAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- ZINC -->
      <tr>
        <td class="table_side_head_wrap">Zinc</td>
        <td class="table_row">
          <span id="sprytextfield77">
            <input name="pdkZincAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkZincAsPackaged'); ?>" id="pdkZincAsPackaged">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
        <td class="table_row">
          <span id="sprytextfield78">
            <input name="pdkZincAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkZincAsPrepared'); ?>" id="pdkZincAsPrepared">
          </span>%
        </td>
        <td class="table_side_head noHighlight">&nbsp;</td>
      </tr>

      <!-- AS PREPARED FOOTNOTE -->
      <tr>
        <td class="table_side_head_wrap">As Prepared Footnote</td>
        <td class="table_row" colspan="4">
          <span id="sprytextarea8">
            <textarea name="pdkAsPreparedFootnote" id="pdkAsPreparedFootnote" cols="45" rows="5"><?php echo $PDK_row->getField('pdkAsPreparedFootnote'); ?></textarea>
          </span>
        </td>
      </tr>
    </table>

<!-- ALLERGEN INFORMATION -->
    <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Allergen Information</td>
      </tr>
      <tr>
        <td class="table_side_head_req" id="pdkAllergensInProductLabel">In Product</td>
        <td class="table_row" id="pdkAllergensInProductTD">
		<?php
			foreach ($pdkAllergens as $displayValue => $value) {
			  if( fmsCompareSet($value, $PDK_row->getField('pdkAllergensInProduct')) ) {
				echo "<input name=\"pdkAllergensInProduct[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\" checked=\"checked\">{$value}<br />";
			  } else {
				echo "<input name=\"pdkAllergensInProduct[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\">{$value}<br />";
			  }
			}
		?>
		</td>
      </tr>
      <tr>
        <td class="table_side_head_req" id="pdkAllergensInFacilityLabel">In Facility</td>
        <td class="table_row" id="pdkAllergensInFacilityTD">
		<?php
			foreach ($pdkAllergens as $displayValue => $value) {
			  if( fmsCompareSet($value, $PDK_row->getField('pdkAllergensInFacility')) ) {
				echo "<input name=\"pdkAllergensInFacility[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\" checked=\"checked\">{$value}<br />";
			  } else {
				echo "<input name=\"pdkAllergensInFacility[{$displayValue}]\" type=\"checkbox\" value=\"{$value}\">{$value}<br />";
			  }
			}
		?>
		</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">May Contain</td>
        <td class="table_row"><span id="sprytextarea7">
          <textarea name="pdkMayContain" id="pdkMayContain" cols="45" rows="5"><?php echo $PDK_row->getField('pdkMayContain'); ?></textarea>
          </span></td>
      </tr>
    </table>

<!-- SYMBOLS -->
    To add symbols, click on the + button in the SYMBOLS field.&nbsp; This will allow you to select from our symbol library or upload your own.
    <table id="SYMBOLS" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Symbols</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header" align="RIGHT">
        <!-- ADD BTN -->
        <?php
        // ASSIGN SYMBOL BUTTON
        if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
            echo $btn_assign_symbol;
        } ?>
        </td>
      </tr>

      <?php if(!$UPC__JobUpcSym_portal->getField('JobUpcSym::_PrimeSymIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="5">No Symbols currently assigned to this SKU.</td>
      </tr>
      <? } ?>

      <?php
$UPC__JobUpcSym_portal_rownum = 1;
foreach(fmsRelatedSet($UPC_row,'JobUpcSym') as $UPC__JobUpcSym_portal_row=>$UPC__JobUpcSym_portal){

	$itemID = $UPC__JobUpcSym_portal->getField('JobUpcSym::_PrimeSymIDX');
	$itemName = $UPC__JobUpcSym_portal->getField('JobUpcSym::symCertifierName');
	$path = $UPC__JobUpcSym_portal->getField('JobUpcSym::sym_thumbnail_path');
	$jsCall = "viewItem('$path')";

?>
		<tr>
          <td class="table_row_borderR" width="50" align="CENTER" onclick="<?php echo $jsCall; ?>"><img src="<?php echo $path; ?>" alt="symbol image" title="View Symbol" height="15"></td>
          <td class="table_row_borderR" nowrap="nowrap"><?php echo $UPC__JobUpcSym_portal->getField('JobUpcSym::symCertifierAcronym'); ?></td>
          <td class="table_row_borderR"><?php echo $UPC__JobUpcSym_portal->getField('JobUpcSym::symCertifierName'); ?></td>
          <td class="table_row" nowrap="nowrap"><?php echo $UPC__JobUpcSym_portal->getField('JobUpcSym::symCategory'); ?></td>

		  <td class="table_row width20" nowrap="nowrap" title="Deassign <?php echo $itemName ?>"><img src="img/Cancel.png" onclick="deassignItem('<?php echo $itemID;?>', 'symbol', '<?php echo $itemName;?>')" /></td>
        </tr>
        <?php if($UPC__JobUpcSym_portal_rownum == 0) break; else $UPC__JobUpcSym_portal_rownum++;
}//portal_end ?>
    </table>

<!-- SKU IMAGERY SUBMISSION -->
  <table id="SKU IMAGERY SUBMISSION" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="table_header" colspan="2">Supplier Package Sample</td>
    </tr>
    <tr>
      <td class="table_side_head_wrap">
      	<p>Printed Sample – Representative printed, packaged product (including lid/cap options, etc.)
      	<br /><br />
        <strong>Galileo Global Branding Group, Inc. would like to investigate any opportunities for unique and innovative packaging.&nbsp; Please provide any alternative, existing package options that could be utilized – e.g., optional label substrates, alternate bottle shapes.</strong>
      </td>
      <td class="table_row_form"><p>Please send physical samples to</p>
        <p>Attn: <?php echo $PDK_row->getField('JobAccSPEC::accNameFML'); ?><br>Galileo Global Branding Group<br>700 Fairfield Avenue<br>Stamford, CT 06902</p>
      </td>
    </tr>
  </table>

	<!-- IMAGERY -->
	To add imagery, click on the + button in the IMAGERY field. This will give you the option of uploading new photographs or illustrations, or choosing from a list of imagery associated with previous jobs executed by Galileo Global Branding Group, Inc..
	<table id="IMAGERY" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td class="table_header" colspan=2>Photography/Imagery
		  <!-- ASSIGN IMAGERY BUTTON -->
			<?php
			  if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
			  	echo $btn_assign_imagery;
			 } ?>
		  </td>
		</tr>

		<tr>
			<td class="table_side_head_req" id="upcExistingImageryLabel">
				<p>Using existing photography may lower overall project costs.</p>
				<p>Is there existing imagery available for us to use on this project?</p>
			</td>
			<td class="table_row_form" id="upcExistingImageryTD">
			<?php
				foreach ($yes_no as $displayValue => $value) {
				  if( fmsCompareSet($value, $PDK_row->getField('pdkExistingImagery')) ) {
					echo "<input name=\"pdkExistingImagery\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
				  } else {
					echo "<input name=\"pdkExistingImagery\" type=\"radio\" value=\"{$value}\">{$value}\n";
				  }
				}
			?>
			</td>
		</tr>

		<?php if(!$UPC__jobUpcImg_portal->getField('jobUpcImg::_PrimeImgIDX')){ ?>
	  <tr>
	  	<td class="table_row_instr" colspan=2>No imagery currently assigned…</td>
	  </tr>
		<? } ?>

		<?php
		$UPC__jobUpcImg_portal_rownum = 1;
		foreach(fmsRelatedSet($UPC_row,'jobUpcImg') as $UPC__jobUpcImg_portal_row=>$UPC__jobUpcImg_portal){

		$itemID = $UPC__jobUpcImg_portal->getField('jobUpcImg::_PrimeImgIDX');
		$itemName = $UPC__jobUpcImg_portal->getField('jobUpcImg::ImgFileName');

		?>
	  <tr id="item">
	    <td class="table_row" colspan=2>
				<div id="itemViewTable" onclick="viewItem('<?php echo $UPC__jobUpcImg_portal->getField('jobUpcImg::ImgPath'); ?>')" title="View Image"></div>
				<div class="itemDeassignRight" title="Deassign <?php echo $itemName; ?>" onclick="deassignItem('<?php echo $itemID;?>', 'image', '<?php echo $itemName;?>')"></div>
				<?php echo $UPC__jobUpcImg_portal->getField('jobUpcImg::ImgFileName'); ?>
		  </td>
	  </tr>
	  <?php if($UPC__jobUpcImg_portal_rownum == 0) break; else $UPC__jobUpcImg_portal_rownum++;
		}//portal_end ?>
	</table>

<!-- DOCUMENTS -->
  To add documents, click on the + button in the DOCUMENTS field. This will give you the option of uploading additional documents to this UPC.
  <table id="DOCUMENTS" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="table_header">DOCUMENTS
	<!-- ADD BTN -->
	<?php
    // ASSIGN DOCUMENT BUTTON
    if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
        echo $btn_assign_documents;
    } ?>
    </td>
  </tr>

  <?php if(!$UPC__JobUpcDoc_portal->getField('JobUpcDoc::_PrimeDocIDX')){ ?>
      <tr>
      	<td class="table_row_instr">Please attach/assign any specific Label Code, collator bar, or other special marks that should be included on this item.</td>
      </tr>
  <? } ?>

  <?php
$UPC__JobUpcDoc_portal_rownum = 1;
foreach(fmsRelatedSet($UPC_row,'JobUpcDoc') as $UPC__JobUpcDoc_portal_row=>$UPC__JobUpcDoc_portal){

	$itemID = $UPC__JobUpcDoc_portal->getField('JobUpcDoc::_PrimeDocIDX');
	$itemName = $UPC__JobUpcDoc_portal->getField('JobUpcDoc::docFileName');

?>
    <tr id="item">
      <td class="table_row">
		<div id="itemViewTable" onclick="viewItem('<?php echo $UPC__JobUpcDoc_portal->getField('JobUpcDoc::docPath'); ?>')" title="View Image"></div>
		<div class="itemDeassignRight" title="Deassign <?php echo $itemName; ?>" onclick="deassignItem('<?php echo $itemID;?>', 'document', '<?php echo $itemName;?>')"></div>
		<?php echo $UPC__JobUpcDoc_portal->getField('JobUpcDoc::docFileName'); ?>
	  </td>
    </tr>
    <?php if($UPC__JobUpcDoc_portal_rownum == 0) break; else $UPC__JobUpcDoc_portal_rownum++;
}//portal_end ?>
  </table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="25">
          <input type="hidden" name="-recid" value="<?php echo $PDK_row->getRecordId(); ?>" />
          <input type="hidden" name="u" value="<?php echo $PDK_row->getField('pdk_FK_upc'); ?>" />
          <input type="hidden" id="status" name="status" value="" />
          <input name="redirect" type="hidden" value="" id="redirect">

          <?php
                // SAVE BUTTON
                if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
                    //echo "<input type=\"button\"  value=\"Save\" onclick=\"savePDKform()\">";
					echo "<a class=\"button_yellow\" onclick=\"savePDKform()\"><span>Save... Not Done Yet</span></a>";
                }
                // SUBMIT BUTTON
                if ($PDKstatusOrig <> "Complete") {
                   // echo "<input type=\"button\" value=\"Submit\" onclick=\"submitPDKform()\">";
					echo "<a class=\"button_green\" onclick=\"submitPDKform()\"><span>Done! Submit to Galileo Global Branding Group, Inc.</span></a>";

                }
            ?>
      </td>
    </tr>
</table>

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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {isRequired:true});
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2", {isRequired:true});
var sprytextarea3 = new Spry.Widget.ValidationTextarea("sprytextarea3");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {isRequired:false});
var sprytextarea4 = new Spry.Widget.ValidationTextarea("sprytextarea4", {isRequired:true});
var sprytextarea5 = new Spry.Widget.ValidationTextarea("sprytextarea5", {isRequired:false});
var sprytextarea6 = new Spry.Widget.ValidationTextarea("sprytextarea6", {isRequired:false});
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11");
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13");
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14");
var sprytextfield15 = new Spry.Widget.ValidationTextField("sprytextfield15", "none", {isRequired:false});
var sprytextfield16 = new Spry.Widget.ValidationTextField("sprytextfield16", "none", {isRequired:false});
var sprytextfield17 = new Spry.Widget.ValidationTextField("sprytextfield17");
var sprytextfield18 = new Spry.Widget.ValidationTextField("sprytextfield18");
var sprytextfield19 = new Spry.Widget.ValidationTextField("sprytextfield19", "none", {isRequired:false});
var sprytextfield20 = new Spry.Widget.ValidationTextField("sprytextfield20", "none", {isRequired:false});
var sprytextfield21 = new Spry.Widget.ValidationTextField("sprytextfield21", "none");
var sprytextfield22 = new Spry.Widget.ValidationTextField("sprytextfield22", "none", {isRequired:false});
var sprytextfield23 = new Spry.Widget.ValidationTextField("sprytextfield23", "none", {isRequired:true});
var sprytextfield24 = new Spry.Widget.ValidationTextField("sprytextfield24", "none", {isRequired:false});
var sprytextfield25 = new Spry.Widget.ValidationTextField("sprytextfield25", "none", {isRequired:true});
var sprytextfield26 = new Spry.Widget.ValidationTextField("sprytextfield26", "none", {isRequired:false});
var sprytextfield27 = new Spry.Widget.ValidationTextField("sprytextfield27");
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28");
var sprytextfield29 = new Spry.Widget.ValidationTextField("sprytextfield29", "none", {isRequired:false});
var sprytextfield30 = new Spry.Widget.ValidationTextField("sprytextfield30", "none", {isRequired:false});
var sprytextfield31 = new Spry.Widget.ValidationTextField("sprytextfield31");
var sprytextfield32 = new Spry.Widget.ValidationTextField("sprytextfield32");
var sprytextfield33 = new Spry.Widget.ValidationTextField("sprytextfield33", "none", {isRequired:false});
var sprytextfield34 = new Spry.Widget.ValidationTextField("sprytextfield34", "none", {isRequired:false});
var sprytextfield35 = new Spry.Widget.ValidationTextField("sprytextfield35", "none", {isRequired:false});
var sprytextfield36 = new Spry.Widget.ValidationTextField("sprytextfield36", "none", {isRequired:false});
var sprytextfield37 = new Spry.Widget.ValidationTextField("sprytextfield37", "none", {isRequired:false});
var sprytextfield38 = new Spry.Widget.ValidationTextField("sprytextfield38", "none", {isRequired:false});
var sprytextfield39 = new Spry.Widget.ValidationTextField("sprytextfield39");
var sprytextfield40 = new Spry.Widget.ValidationTextField("sprytextfield40");
var sprytextfield41 = new Spry.Widget.ValidationTextField("sprytextfield41", "none", {isRequired:false});
var sprytextfield42 = new Spry.Widget.ValidationTextField("sprytextfield42", "none", {isRequired:false});
var sprytextfield43 = new Spry.Widget.ValidationTextField("sprytextfield43");
var sprytextfield44 = new Spry.Widget.ValidationTextField("sprytextfield44");
var sprytextfield45 = new Spry.Widget.ValidationTextField("sprytextfield45", "none", {isRequired:false});
var sprytextfield46 = new Spry.Widget.ValidationTextField("sprytextfield46", "none", {isRequired:false});
var sprytextfield47 = new Spry.Widget.ValidationTextField("sprytextfield47");
var sprytextfield48 = new Spry.Widget.ValidationTextField("sprytextfield48", "none", {isRequired:false});
var sprytextfield49 = new Spry.Widget.ValidationTextField("sprytextfield49");
var sprytextfield50 = new Spry.Widget.ValidationTextField("sprytextfield50", "none", {isRequired:false});
var sprytextfield51 = new Spry.Widget.ValidationTextField("sprytextfield51");
var sprytextfield52 = new Spry.Widget.ValidationTextField("sprytextfield52", "none", {isRequired:false});
var sprytextfield53 = new Spry.Widget.ValidationTextField("sprytextfield53");
var sprytextfield54 = new Spry.Widget.ValidationTextField("sprytextfield54", "none", {isRequired:false});
var sprytextfield55 = new Spry.Widget.ValidationTextField("sprytextfield55");
var sprytextfield56 = new Spry.Widget.ValidationTextField("sprytextfield56", "none", {isRequired:false});
var sprytextfield57 = new Spry.Widget.ValidationTextField("sprytextfield57");
var sprytextfield58 = new Spry.Widget.ValidationTextField("sprytextfield58", "none", {isRequired:false});
var sprytextfield59 = new Spry.Widget.ValidationTextField("sprytextfield59", "none", {isRequired:false});
var sprytextfield60 = new Spry.Widget.ValidationTextField("sprytextfield60", "none", {isRequired:false});
var sprytextfield61 = new Spry.Widget.ValidationTextField("sprytextfield61", "none", {isRequired:false});
var sprytextfield62 = new Spry.Widget.ValidationTextField("sprytextfield62", "none", {isRequired:false});
var sprytextfield63 = new Spry.Widget.ValidationTextField("sprytextfield63", "none", {isRequired:false});
var sprytextfield64 = new Spry.Widget.ValidationTextField("sprytextfield64", "none", {isRequired:false});
var sprytextfield65 = new Spry.Widget.ValidationTextField("sprytextfield65", "none", {isRequired:false});
var sprytextfield66 = new Spry.Widget.ValidationTextField("sprytextfield66", "none", {isRequired:false});
var sprytextfield67 = new Spry.Widget.ValidationTextField("sprytextfield67", "none", {isRequired:false});
var sprytextfield68 = new Spry.Widget.ValidationTextField("sprytextfield68", "none", {isRequired:false});
var sprytextfield69 = new Spry.Widget.ValidationTextField("sprytextfield69", "none", {isRequired:false});
var sprytextfield70 = new Spry.Widget.ValidationTextField("sprytextfield70", "none", {isRequired:false});
var sprytextfield71 = new Spry.Widget.ValidationTextField("sprytextfield71", "none", {isRequired:false});
var sprytextfield72 = new Spry.Widget.ValidationTextField("sprytextfield72", "none", {isRequired:false});
var sprytextfield73 = new Spry.Widget.ValidationTextField("sprytextfield73", "none", {isRequired:false});
var sprytextfield74 = new Spry.Widget.ValidationTextField("sprytextfield74", "none", {isRequired:false});
var sprytextfield75 = new Spry.Widget.ValidationTextField("sprytextfield75", "none", {isRequired:false});
var sprytextfield76 = new Spry.Widget.ValidationTextField("sprytextfield76", "none", {isRequired:false});
var sprytextfield77 = new Spry.Widget.ValidationTextField("sprytextfield77", "none", {isRequired:false});
var sprytextfield78 = new Spry.Widget.ValidationTextField("sprytextfield78", "none", {isRequired:false});
var sprytextarea7 = new Spry.Widget.ValidationTextarea("sprytextarea7", {isRequired:false});
var sprytextfield79 = new Spry.Widget.ValidationTextField("sprytextfield79", "none", {isRequired:false});
var sprytextfield80 = new Spry.Widget.ValidationTextField("sprytextfield80", "none", {isRequired:false});
var sprytextarea8 = new Spry.Widget.ValidationTextarea("sprytextarea8", {isRequired:false});
var sprytextfield81 = new Spry.Widget.ValidationTextField("sprytextfield81", "none", {isRequired:false});
var sprytextarea9 = new Spry.Widget.ValidationTextarea("sprytextarea9", {isRequired:false});
var sprytextfield82 = new Spry.Widget.ValidationTextField("sprytextfield82", "none", {isRequired:false});
var sprytextfield83 = new Spry.Widget.ValidationTextField("sprytextfield83", "none", {isRequired:false});
var sprytextfield84 = new Spry.Widget.ValidationTextField("sprytextfield84", "none", {isRequired:false});
var sprytextfield85 = new Spry.Widget.ValidationTextField("sprytextfield85", "none", {isRequired:false});
var sprytextfield86 = new Spry.Widget.ValidationTextField("sprytextfield86", "none", {isRequired:false});
var sprytextfield87 = new Spry.Widget.ValidationTextField("sprytextfield87", "none", {isRequired:false});
var sprytextfield88 = new Spry.Widget.ValidationTextField("sprytextfield88", "none", {isRequired:false});
var sprytextfield89 = new Spry.Widget.ValidationTextField("sprytextfield89", "none", {isRequired:false});
var sprytextfield90 = new Spry.Widget.ValidationTextField("sprytextfield90", "none", {isRequired:false});
var sprytextarea10 = new Spry.Widget.ValidationTextarea("sprytextarea10", {isRequired:false});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>

<form id="deassign_form" action="sku_deassign_response.php" method="post" enctype="multipart/form-data" name="deassign_form">
	<input type="hidden" id="-recid" name="-recid" value="<?php echo $UPC_row->getRecordId(); ?>" />
    <input type="hidden" id="u" name="u" value="<?php echo $UPC_row->getField('upc_PK'); ?>" />
    <input type="hidden" id="deassign_field" name="deassign_field" value="" />
	<input type="hidden" id="deassign_item" name="deassign_item" value="" />
	<input type="hidden" id="deassign_name" name="deassign_name" value="" />
</form>

</body>
</html>
