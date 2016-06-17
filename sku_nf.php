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

$layoutPDK = $ContactLogin->getLayout('sup-PDK');
$pdkAllergens = $layoutPDK->getValueListTwoFields("pdkAllergens");
$pdkHealthClaims = $layoutPDK->getValueListTwoFields("pdkHealthClaims");
$pdkMethodOfApplication = $layoutPDK->getValueListTwoFields("pdkMethodOfApplication");
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

<script type="text/javascript">
$(document).ready(function(){
	var PDKstatus = '<?php echo $PDKstatus; ?>';
	var infoReturned = '<?php echo $skuInfoReturned; ?>';
	var infoPassFail = '<?php echo $skuInfoPassFail; ?>';
	document.getElementById("status").value= PDKstatus;

	toggleInfoPanel(infoReturned, infoPassFail);

	$("#addBtn a").hover(function() {
		$(this).next("em").animate({opacity: "show", top: "-75"}, "slow");
	}, function() {
		$(this).next("em").animate({opacity: "hide", top: "-85"}, "fast");
	});

	$("#DIELINES tr, #IMAGERY tr, #SYMBOLS tr, #LABEL_CODE_INFORMATION tr").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
    );

	// NEW SCRIPT - COMPLETE STATUS HIDE INPUTS, ETC.
	if (PDKstatus == "Complete" || PDKstatus == "Submitted"){
		var value = '';
		$(".itemDeassignRight").remove();
		$(":input[type='hidden']").remove();
		$('input, textarea, select').attr("disabled", "disabled").show();
	}

	// BUTTONS
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
	})

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
	})

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
	})

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
	})

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
	})

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
	})

	$("ul#addBtn[name='btn_assign_lci']").qtip({
		show: 'mouseover',
		hide: 'mouseout',
		content: '<?php if ($PDK_row->getField('pdk_FK_lci')) { echo "Change Label Code"; } else { echo "Add Label Code"; } ?>',
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

});

function submitPDKform(){
	var myForm = document.getElementById("edit_pdk");
	var SS = Spry.Widget.Form.validate(myForm);
	var upc_FK_die = '<?php echo $UPC__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX'); ?>';
	var upc_FK_spe = '<?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX'); ?>';
	var assigned_printers = '<?php echo $UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK'); ?>';

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
		$("#PRINTERS td[name='printerSpecHeader']").removeClass("table_headerReq");
		$("#PRINTERS td[name='printerSpecHeader']").addClass("table_header");
	} else {
		SS = "false";
		$("#PRINTERS td[name='printerSpecHeader']").removeClass("table_header");
		$("#PRINTERS td[name='printerSpecHeader']").addClass("table_headerReq");
		$("#PRINTERS td[name='noSpecInstr']").removeClass("table_row_instr");
		$("#PRINTERS td[name='noSpecInstr']").addClass("table_row_instr_req");
	}

	if(upc_FK_die != ''){
		$("#DIELINES td[name='dielineHeader']").removeClass("table_headerReq");
		$("#DIELINES td[name='dielineHeader']").addClass("table_header");
	} else {
		SS = "false";
		$("#DIELINES td[name='dielineHeader']").removeClass("table_header");
		$("#DIELINES td[name='dielineHeader']").addClass("table_headerReq");
		$("#DIELINES td[name='noDieInstr']").removeClass("table_row_instr");
		$("#DIELINES td[name='noDieInstr']").addClass("table_row_instr_req");
	}

	if(!$("#pdkPackageConfigurationTD input:checked").val()){
		SS = "false";
		$("#pdkPackageConfigurationLabel").removeClass("table_row");
		$("#pdkPackageConfigurationTD").removeClass("table_row");
		$("#pdkPackageConfigurationLabel").addClass("required_jquery");
		$("#pdkPackageConfigurationTD").addClass("required_jquery");
	} else {
		$("#pdkPackageConfigurationLabel").removeClass("required_jquery");
		$("#pdkPackageConfigurationTD").removeClass("required_jquery");
		$("#pdkPackageConfigurationLabel").addClass("table_row");
		$("#pdkPackageConfigurationTD").addClass("table_row");
	}

	if(!$("#upcExistingImageryTD input:checked").val()){
		SS = "false";
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
    <h1>SKU Detail (non-food)</h1>
  </div><!-- end #header -->

  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

   <? if($PDKstatus != "Complete"  && $PDKstatus != "Submitted"){ ?>
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
	if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
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
    To add dielines, click on the + button in the DIELINES field.&nbsp; This will give you the option of uploading a new dieline, or choosing from a list of dielines associated with previous jobs.
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
        <td class="table_row"><span id="sprytextfield1">
          <input name="pdkProductName" type="text" value="<?php echo $PDK_row->getField('pdkProductName'); ?>" id="pdkProductName">
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Net Weight / Size &amp; Count</td>
        <td class="table_row"><span id="sprytextfield2">
          <input name="pdkNetWeight" type="text" value="<?php echo $PDK_row->getField('pdkNetWeight'); ?>" id="pdkNetWeight">
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Secondary Descriptors</td>
        <td class="table_row"><span id="sprytextarea1">
          <textarea name="pdkSecondaryDescriptors" id="pdkSecondaryDescriptors" cols="45" rows="5"><?php echo $PDK_row->getField('pdkSecondaryDescriptors'); ?></textarea>
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head"><p>Available Trademarks / <br />
          Brand Names</p></td>
        <td class="table_row"><span id="sprytextfield79">
          <input name="pdkAvailableTrademarks" type="text" value="<?php echo $PDK_row->getField('pdkAvailableTrademarks'); ?>" id="pdkAvailableTrademarks">
</span></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Product Claims / Features / Qualifiers</td>
        <td class="table_row"><span id="sprytextarea2">
          <textarea name="pdkProductClaims" id="pdkProductClaims" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductClaims'); ?></textarea>
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Ingredient Statement</td>
        <td class="table_row"><span id="sprytextarea3">
          <textarea name="pdkIngredientStatement" id="pdkIngredientStatement" cols="45" rows="5"><?php echo $PDK_row->getField('pdkIngredientStatement'); ?></textarea>
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Country of Origin</td>
        <td class="table_row"><span id="sprytextfield3">
          <input name="pdkCountryOfOrigin" type="text" value="<?php echo $PDK_row->getField('pdkCountryOfOrigin'); ?>" id="pdkCountryOfOrigin">
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Product Use /<br />
          Storage Instructions </td>
        <td class="table_row"><span id="sprytextarea4">
          <textarea name="pdkProductUseStorage" id="pdkProductUseStorage" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductUseStorage'); ?></textarea>
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head">Warnings/Caution Statements – Front Panel</td>
        <td class="table_row"><span id="sprytextarea6">
          <textarea name="pdkWarningStatements" id="pdkWarningStatements" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatements'); ?></textarea>
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Warnings/Caution Statements – Back/Side Panel</td>
        <td class="table_row"><span id="sprytextarea5">
          <textarea name="pdkWarningStatementsBackSidePanel" id="pdkWarningStatementsBackSidePanel" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatementsBackSidePanel'); ?></textarea>
</span></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Package Components:<br>Cap/Lid Colors etc.</td>
        <td class="table_row"><span id="sprytextfield4">
          <input name="pdkPackageComponents" type="text" value="<?php echo $PDK_row->getField('pdkPackageComponents'); ?>" id="pdkPackageComponents">
          </span></td>
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
        <td class="table_side_head">Is this product tested on animals?</td>
        <td class="table_row">
				<?php
					foreach ($yes_no as $displayValue => $value) {
					  if( fmsCompareSet($value, $PDK_row->getField('pdkTestedOnAnimals')) ) {
						echo "<input name=\"pdkTestedOnAnimals\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
					  } else {
						echo "<input name=\"pdkTestedOnAnimals\" type=\"radio\" value=\"{$value}\">{$value}\n";
					  }
					}
				?>
		</td>
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

<!-- SKU DATE CODING -->
<table id="SKU DATE CODING" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="table_header" colspan="2">SKU Date Coding Information</td>
	</tr>
	<tr>
		<td class="table_side_head_wrap">Description / Location of Code on Package</td>
		<td class="table_row_form">
			<span id="sprytextfield8">
				<input name="pdkDescription" type="text" value="<?php echo $PDK_row->getField('pdkDescription'); ?>" id="pdkDescription">
				<span class="textfieldRequiredMsg">A value is required.</span>
			</span>
		</td>
	</tr>
	<tr>
		<td class="table_side_head_wrap">Example</td>
		<td class="table_row_form">
			<span id="sprytextfield9">
				<input name="pdkExample" type="text" value="<?php echo $PDK_row->getField('pdkExample'); ?>" id="pdkExample">
				<span class="textfieldRequiredMsg">A value is required.</span>
			</span>
		</td>
	</tr>
	<tr>
		<td class="table_side_head_wrap">Method of Application</td>
		<td class="table_row_form">
		<?php
			foreach ($pdkMethodOfApplication as $displayValue => $value) {
				if( fmsCompareSet($value, $PDK_row->getField('pdkMethodOfApplication')) ) {
					echo "<input name=\"pdkMethodOfApplication\" type=\"radio\" value=\"{$value}\" checked=\"checked\">{$value}\n";
				} else {
					echo "<input name=\"pdkMethodOfApplication\" type=\"radio\" value=\"{$value}\">{$value}\n";
				}
			}
		?>
		</td>
	</tr>
</table>

	<!-- SKU IMAGERY SUBMISSION -->
	<table id="SKU IMAGERY SUBMISSION" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="table_header" colspan="2">Supplier Package Sample</td>
		</tr>
		<tr>
			<td class="table_side_head_wrap">
				<p>
					Printed Sample – Representative printed, packaged product (including lid/cap options, etc.)<br /><br />
					<strong>Galileo Global Branding Group, Inc. would like to investigate any opportunities for unique and innovative packaging. Please provide any alternative, existing package options that could be utilized<br />– </strong>
					<strong>e.g., optional label substrates, alternate bottle shapes.</strong>
				</p>
			</td>
			<td class="table_row_form"><p>Please send physical samples to</p>
				<p>
					Attn: <?php echo $PDK_row->getField('JobAccSPEC::accNameFML'); ?><br>
					Galileo Global Branding Group<br>
					700 Fairfield Avenue<br>
					Stamford, CT 06902
				</p>
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
			<td class="table_row_instr" colspan=2>No Imagery currently assigned to this SKU.</td>
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

<!-- HIDDEN FIELD INFORMATION -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td height="25">
          <input type="hidden" name="-recid" value="<?php echo $PDK_row->getRecordId(); ?>" />
          <input type="hidden" name="u" value="<?php echo $PDK_row->getField('pdk_FK_upc'); ?>" />
          <input type="hidden" id="status" name="status" value="" />
          <input name="redirect" type="hidden" value="" id="redirect" size="40">

          <?php
                // SAVE BUTTON
                if ($PDKstatusOrig <> "Submitted" && $PDKstatusOrig <> "Complete") {
					echo "<a class=\"button_yellow\" onclick=\"savePDKform()\"><span>Save... Not Done Yet</span></a>";
                }
                // SUBMIT BUTTON
                if ($PDKstatusOrig <> "Complete") {
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
  </h1>
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
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2", {isRequired:false});
var sprytextarea3 = new Spry.Widget.ValidationTextarea("sprytextarea3");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {isRequired:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {isRequired:false});
var sprytextarea6 = new Spry.Widget.ValidationTextarea("sprytextarea6", "none", {isRequired:false});
var sprytextfield79 = new Spry.Widget.ValidationTextField("sprytextfield79", "none", {isRequired:false});
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4", {isRequired:false});
var sprytextarea4 = new Spry.Widget.ValidationTextarea("sprytextarea4");
var sprytextarea5 = new Spry.Widget.ValidationTextarea("sprytextarea5", {isRequired:false});
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
