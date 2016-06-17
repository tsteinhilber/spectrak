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

<?php // Set PDKstatus based upon the field upcPDKstatus

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

<!-- HTML START -->

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>SpecTrak | SKU: <?php echo $UPC_row->getField('JobUpcSku::skuName'); ?></title>
	<link href="css/specStyle.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
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

	<!-- JAVASCRIPT -->
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="js/sku.js"></script>
	<script type="text/javascript" src="js/application.js"></script>

	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

	<script>
		var PDKstatus = '<?php echo $PDKstatus; ?>';
		var infoReturned = '<?php echo $skuInfoReturned; ?>';
		var infoPassFail = '<?php echo $skuInfoPassFail; ?>';
		var PDKstatusOrig = '<?php echo $PDKstatusOrig; ?>';
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
        <td class="table_row">
          <input name="pdkProductName" type="text" value="<?php echo $PDK_row->getField('pdkProductName'); ?>" id="pdkProductName">
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Net Weight / Size &amp; Count</td>
        <td class="table_row">
          <input required name="pdkNetWeight" type="text" value="<?php echo $PDK_row->getField('pdkNetWeight'); ?>" id="pdkNetWeight">
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Secondary Descriptors</td>
        <td class="table_row">
          <textarea required name="pdkSecondaryDescriptors" id="pdkSecondaryDescriptors" cols="45" rows="5"><?php echo $PDK_row->getField('pdkSecondaryDescriptors'); ?></textarea>
        </td>
      </tr>
	  
	  
	  <!-- COOL -->
      <tr id="pdkCOOL">
        <td class="table_side_head_req">If this product is distributed in the USA, is origin statement required under COOL?</td>
        <td class="table_row">
			<?php
			foreach(fmsValueListItems($ContactLogin,'sup-PDK','Yes/No',"") as $list_item_key=>$list_item) {
			  if( fmsCompareSet($list_item, $PDK_row->getField('pdkCOOL')) ) {
				echo "<input name=\"pdkCOOL\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
			  } else {
				echo "<input name=\"pdkCOOL\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
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
          <textarea name="pdkProductClaims" id="pdkProductClaims" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductClaims'); ?></textarea>
        </td>
      </tr>
	  
	  <!-- HEALTH CLAIMS -->
      <tr>
        <td class="table_side_head_req" id="pdkHealthClaimsLabel">Health Claims</td>
        <td class="table_row" id="pdkHealthClaimsTD">
        	<?php
foreach(fmsValueListItems($ContactLogin,'sup-PDK','pdkHealthClaims',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $PDK_row->getField('pdkHealthClaims')) ) {
    echo "<input name=\"pdkHealthClaims[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"pdkHealthClaims[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?>
		</td>
      </tr>
	  
	  <!-- PRODUCT CLAIMS OPTIONAL -->
      <tr>
        <td class="table_side_head">Product Claims Optional</td>
        <td class="table_row">
          <textarea name="pdkProductClaimsOptional" id="pdkProductClaimsOptional" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductClaimsOptional'); ?></textarea>
		</td>
      </tr>
	  
	  <!-- INGREDIENT CLAIMS -->
      <tr>
        <td class="table_side_head_req">Ingredient Statement</td>
        <td class="table_row">
          <textarea name="pdkIngredientStatement" id="pdkIngredientStatement" cols="45" rows="5"><?php echo $PDK_row->getField('pdkIngredientStatement'); ?></textarea>
        </td>
      </tr>

	  <!-- PACKAGE COMPONENTS -->
      <tr>
        <td class="table_side_head_wrap">Package Components:<br>Cap/Lid Colors etc.</td>
        <td class="table_row">
          <input name="pdkPackageComponents" type="text" value="<?php echo $PDK_row->getField('pdkPackageComponents'); ?>" id="pdkPackageComponents">
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req" id="pdkPackageConfigurationLabel">Package Configuration</td>
        <td class="table_row" id="pdkPackageConfigurationTD">
          <?php
foreach(fmsValueListItems($ContactLogin,'sup-PDK','pdkPackageConfiguration',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $PDK_row->getField('pdkPackageConfiguration')) ) {
    echo "<input name=\"pdkPackageConfiguration[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
  } else {
    echo "<input name=\"pdkPackageConfiguration[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\">{$list_item}\n";
  }
}
?></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Product Preparation Instructions and/or Storage Instructions</td>
        <td class="table_row">
          <textarea name="pdkProductPreparationInstructions" id="pdkProductPreparationInstructions" cols="45" rows="5"><?php echo $PDK_row->getField('pdkProductPreparationInstructions'); ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Recipe and/or Romance Copy</td>
        <td class="table_row">
          <textarea name="pdkRecipe" id="pdkRecipe" cols="45" rows="5"><?php echo $PDK_row->getField('pdkRecipe'); ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Warnings/Caution Statements – Front Panel</td>
        <td class="table_row">
          <textarea name="pdkWarningStatements" id="pdkWarningStatements" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatements'); ?></textarea>
        ></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Warnings/Caution Statements – Back/Side Panel</td>
        <td class="table_row">
          <textarea name="pdkWarningStatementsBackSidePanel" id="pdkWarningStatementsBackSidePanel" cols="45" rows="5"><?php echo $PDK_row->getField('pdkWarningStatementsBackSidePanel'); ?></textarea>
		</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">USDA Establishment Number</td>
        <td class="table_row">
          <input name="pdkUsdaNum" type="text" value="<?php echo $PDK_row->getField('pdkUsdaNum'); ?>" id="pdkUsdaNum">
		</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Certified 3rd Party</td>
        <td class="table_row">
          <input name="pdkProductCertified3rdParty" type="text" value="<?php echo $PDK_row->getField('pdkProductCertified3rdParty'); ?>" id="pdkProductCertified3rdParty">
		</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Does this product use sustainable packaging?</td>
        <td class="table_row">
			<?php
			foreach(fmsValueListItems($ContactLogin,'sup-PDK','Yes/No',"") as $list_item_key=>$list_item) {
			  if( fmsCompareSet($list_item, $PDK_row->getField('pdkProductSustainablePackaging')) ) {
				echo "<input name=\"pdkProductSustainablePackaging\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
			  } else {
				echo "<input name=\"pdkProductSustainablePackaging\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
			  }
			}
			?>
          </span>
		  
		  </td>
      </tr>
    </table>
    <table id="nutrition_info" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="5">Nutrition  Information (NLEA)</td>
      </tr>
      <tr>
        <td class="table_sub_head">Net Weight (per item)</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Serving Size</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkServingSizeAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkServingSizeAsPackaged'); ?>" id="pdkServingSizeAsPackaged">
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkServingSizeAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkServingSizeAsPrepared'); ?>" id="pdkServingSizeAsPrepared">
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Servings per Container</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkServingsPerContainerAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkServingsPerContainerAsPackaged'); ?>" id="pdkServingsPerContainerAsPackaged">
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkServingsPerContainerAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkServingsPerContainerAsPrepared'); ?>" id="pdkServingsPerContainerAsPrepared">
        ></td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_sub_head">Amount per Serving</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Calories</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCaloriesAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesAsPackaged'); ?>" id="pdkCaloriesAsPackaged">
        </td>
        <td class="table_row_borderR" width="20%"></td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCaloriesAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesAsPrepared'); ?>" id="pdkCaloriesAsPrepared">
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Calories from Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCaloriesFromFatAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesFromFatAsPackaged'); ?>" id="pdkCaloriesFromFatAsPackaged">
        </td>
        <td class="table_row_borderR" width="20%">&nbsp;</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCaloriesFromFatAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkCaloriesFromFatAsPrepared'); ?>" id="pdkCaloriesFromFatAsPrepared">
        </td>
        <td class="table_row" width="20%">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Total Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPackagedG'); ?>" id="pdkTotalFatAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalFatAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPackagedPerc'); ?>" id="pdkTotalFatAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPreparedG'); ?>" id="pdkTotalFatAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">
          <input name="pdkTotalFatAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalFatAsPreparedPerc'); ?>" id="pdkTotalFatAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Saturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPackagedG'); ?>" id="pdkSaturatedFatAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSaturatedFatAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPackagedPerc'); ?>" id="pdkSaturatedFatAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPreparedG'); ?>" id="pdkSaturatedFatAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">
          <input name="pdkSaturatedFatAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSaturatedFatAsPreparedPerc'); ?>" id="pdkSaturatedFatAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Trans Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTransFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTransFatAsPackagedG'); ?>" id="pdkTransFatAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%"></td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTransFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTransFatAsPreparedG'); ?>" id="pdkTransFatAsPreparedG"> g
        </td>
        <td class="table_row" width="20%"></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Polyunsaturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkPolyunsaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkPolyunsaturatedFatAsPackagedG'); ?>" id="pdkPolyunsaturatedFatAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%"></td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkPolyunsaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkPolyunsaturatedFatAsPreparedG'); ?>" id="pdkPolyunsaturatedFatAsPreparedG"> g
        </td>
        <td class="table_row" width="20%"></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Monounsaturated Fat</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkMonounsaturatedFatAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkMonounsaturatedFatAsPackagedG'); ?>" id="pdkMonounsaturatedFatAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%"></td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkMonounsaturatedFatAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkMonounsaturatedFatAsPreparedG'); ?>" id="pdkMonounsaturatedFatAsPreparedG"> g
        </td>
        <td class="table_row" width="20%"></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Cholesterol</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCholesterolAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPackagedMG'); ?>" id="pdkCholesterolAsPackagedMG"> mg
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCholesterolAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPackagedPerc'); ?>" id="pdkCholesterolAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkCholesterolAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPreparedMG'); ?>" id="pdkCholesterolAsPreparedMG"> mg
        </td>
        <td class="table_row" width="20%">
          <input name="pdkCholesterolAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkCholesterolAsPreparedPerc'); ?>" id="pdkCholesterolAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Sodium</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSodiumAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPackagedMG'); ?>" id="pdkSodiumAsPackagedMG"> mg
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSodiumAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPackagedPerc'); ?>" id="pdkSodiumAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSodiumAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPreparedMG'); ?>" id="pdkSodiumAsPreparedMG"> mg
        </td>
        <td class="table_row" width="20%">
          <input name="pdkSodiumAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkSodiumAsPreparedPerc'); ?>" id="pdkSodiumAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Potassium</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkPotassiumAsPackagedMG" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPackagedMG'); ?>" id="pdkPotassiumAsPackagedMG"> mg
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkPotassiumAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPackagedPerc'); ?>" id="pdkPotassiumAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%"
          <input name="pdkPotassiumAsPreparedMG" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPreparedMG'); ?>" id="pdkPotassiumAsPreparedMG"> mg
        </td>
        <td class="table_row" width="20%">
          <input name="pdkPotassiumAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkPotassiumAsPreparedPerc'); ?>" id="pdkPotassiumAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Total Carbohydrate</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalCarbohydrateAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPackagedG'); ?>" id="pdkTotalCarbohydrateAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalCarbohydrateAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPackagedPerc'); ?>" id="pdkTotalCarbohydrateAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkTotalCarbohydrateAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPreparedG'); ?>" id="pdkTotalCarbohydrateAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">
          <input name="pdkTotalCarbohydrateAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkTotalCarbohydrateAsPreparedPerc'); ?>" id="pdkTotalCarbohydrateAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Dietary Fiber</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkDietaryFiberAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPackagedG'); ?>" id="pdkDietaryFiberAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkDietaryFiberAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPackagedPerc'); ?>" id="pdkDietaryFiberAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkDietaryFiberAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPreparedG'); ?>" id="pdkDietaryFiberAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">
          <input name="pdkDietaryFiberAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkDietaryFiberAsPreparedPerc'); ?>" id="pdkDietaryFiberAsPreparedPerc"> %
       </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Sugars</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSugarsAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAsPackagedG'); ?>" id="pdkSugarsAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%"></td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkSugarsAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkSugarsAsPreparedG'); ?>" id="pdkSugarsAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">&</td>
      </tr>
      <tr>
        <td class="table_side_head_req">Protein</td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkProteinAsPackagedG" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPackagedG'); ?>" id="pdkProteinAsPackagedG"> g
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkProteinAsPackagedPerc" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPackagedPerc'); ?>" id="pdkProteinAsPackagedPerc"> %
        </td>
        <td class="table_row_borderR" width="20%">
          <input name="pdkProteinAsPreparedG" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPreparedG'); ?>" id="pdkProteinAsPreparedG"> g
        </td>
        <td class="table_row" width="20%">
          <input name="pdkProteinAsPreparedPerc" type="text" value="<?php echo $PDK_row->getField('pdkProteinAsPreparedPerc'); ?>" id="pdkProteinAsPreparedPerc"> %
        </td>
      </tr>
      <tr>
        <td class="table_sub_head">Vitamin Infomation</td>
        <td class="table_sub_head" colspan="2">As Packaged</td>
        <td class="table_sub_head" colspan="2">As Prepared</td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_req">Vitamin A</td>
        <td class="table_row">
          <input name="pdkVitaminAAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminAAsPackaged'); ?>" id="pdkVitaminAAsPackaged"> %
        </td>
        <td class="table_side_head noHighlight">Vitamin A</td>
        <td class="table_row">
          <input name="pdkVitaminAAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminAAsPrepared'); ?>" id="pdkVitaminAAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_req">Vitamin C</td>
        <td class="table_row">
          <input name="pdkVitaminCAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminCAsPackaged'); ?>" id="pdkVitaminCAsPackaged"> %
        </td>
        <td class="table_side_head noHighlight">Vitamin C</td>
        <td class="table_row">
          <input name="pdkVitaminCAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminCAsPrepared'); ?>" id="pdkVitaminCAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_req">Calcium</td>
        <td class="table_row">
          <input name="pdkCalciumAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPackaged'); ?>" id="pdkCalciumAsPackaged"> %
        </td>
        <td class="table_side_head noHighlight">Calcium</td>
        <td class="table_row">
          <input name="pdkCalciumAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkCalciumAsPrepared'); ?>" id="pdkCalciumAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_req">Iron</td>
        <td class="table_row">
          <input name="pdkIronAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPackaged'); ?>" id="pdkIronAsPackaged"> %
        </td>
        <td class="table_side_head noHighlight">Iron</td>
        <td class="table_row">
          <input name="pdkIronAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkIronAsPrepared'); ?>" id="pdkIronAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head">Vitamin D</td>
        <td class="table_row">
          <input name="pdkVitaminDAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPackaged'); ?>" id="pdkVitaminDAsPackaged"> %
        </td>
        <td class="table_side_head noHighlight">Vitamin D</td>
        <td class="table_row">
          <input name="pdkVitaminDAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminDAsPrepared'); ?>" id="pdkVitaminDAsPrepared"> %
         </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Vitamin E</td>
        <td class="table_row">
          <input name="pdkVitaminEAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminEAsPackaged'); ?>" id="pdkVitaminEAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Vitamin E</td>
        <td class="table_row">
          <input name="pdkVitaminEAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminEAsPrepared'); ?>" id="pdkVitaminEAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Thiamin</td>
        <td class="table_row">
          <input name="pdkThiaminAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkThiaminAsPackaged'); ?>" id="pdkThiaminAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Thiamin</td>
        <td class="table_row">
          <input name="pdkThiaminAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkThiaminAsPrepared'); ?>" id="pdkThiaminAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Riboflavin</td>
        <td class="table_row">
          <input name="pdkRiboflavinAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkRiboflavinAsPackaged'); ?>" id="pdkRiboflavinAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Riboflavin</td>
        <td class="table_row">
          <input name="pdkRiboflavinAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkRiboflavinAsPrepared'); ?>" id="pdkRiboflavinAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Niacin</td>
        <td class="table_row">
          <input name="pdkNiacinAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkNiacinAsPackaged'); ?>" id="pdkNiacinAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Niacin</td>
        <td class="table_row">
          <input name="pdkNiacinAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkNiacinAsPrepared'); ?>" id="pdkNiacinAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Vitamin B6</td>
        <td class="table_row">
          <input name="pdkVitaminB6AsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB6AsPackaged'); ?>" id="pdkVitaminB6AsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Vitamin B6</td>
        <td class="table_row">
          <input name="pdkVitaminB6AsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB6AsPrepared'); ?>" id="pdkVitaminB6AsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Folic Acid</td>
        <td class="table_row">
          <input name="pdkFolicAcidAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkFolicAcidAsPackaged'); ?>" id="pdkFolicAcidAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Folic Acid</td>
        <td class="table_row">
          <input name="pdkFolicAcidAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkFolicAcidAsPrepared'); ?>" id="pdkFolicAcidAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Vitamin B12</td>
        <td class="table_row">
          <input name="pdkVitaminB12AsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB12AsPackaged'); ?>" id="pdkVitaminB12AsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Vitamin B12</td>
        <td class="table_row">
          <input name="pdkVitaminB12AsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkVitaminB12AsPrepared'); ?>" id="pdkVitaminB12AsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Phosphorus</td>
        <td class="table_row">
          <input name="pdkPhosphorusAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkPhosphorusAsPackaged'); ?>" id="pdkPhosphorusAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Phosphorus</td>
        <td class="table_row">
          <input name="pdkPhosphorusAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkPhosphorusAsPrepared'); ?>" id="pdkPhosphorusAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Magnesium</td>
        <td class="table_row">
          <input name="pdkMagnesiumAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkMagnesiumAsPackaged'); ?>" id="pdkMagnesiumAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Magnesium</td>
        <td class="table_row">
          <input name="pdkMagnesiumAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkMagnesiumAsPrepared'); ?>" id="pdkMagnesiumAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td id="blank"></td>
        <td class="table_side_head_wrap">Zinc</td>
        <td class="table_row">
          <input name="pdkZincAsPackaged" type="text" value="<?php echo $PDK_row->getField('pdkZincAsPackaged'); ?>" id="pdkZincAsPackaged"> %
        </td>
        <td class="table_side_head_wrap noHighlight">Zinc</td>
        <td class="table_row">
          <input name="pdkZincAsPrepared" type="text" value="<?php echo $PDK_row->getField('pdkZincAsPrepared'); ?>" id="pdkZincAsPrepared"> %
        </td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">As Prepared Footnote</td>
        <td class="table_row" colspan="4">
          <textarea name="pdkAsPreparedFootnote" id="pdkAsPreparedFootnote" cols="45" rows="5"><?php echo $PDK_row->getField('pdkAsPreparedFootnote'); ?></textarea>
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
foreach(fmsValueListItems($ContactLogin,'sup-PDK','pdkAllergens',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $PDK_row->getField('pdkAllergensInProduct')) ) {
    echo "<input name=\"pdkAllergensInProduct[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\" checked=\"checked\">{$list_item}<br />";
  } else {
    echo "<input name=\"pdkAllergensInProduct[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\">{$list_item}<br />";
  }
}
?></td>
      </tr>
      <tr>
        <td class="table_side_head_req" id="pdkAllergensInFacilityLabel">In Facility</td>
        <td class="table_row" id="pdkAllergensInFacilityTD">
          <?php
foreach(fmsValueListItems($ContactLogin,'sup-PDK','pdkAllergens',"") as $list_item_key=>$list_item) {
  if( fmsCompareSet($list_item, $PDK_row->getField('pdkAllergensInFacility')) ) {
    echo "<input name=\"pdkAllergensInFacility[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\" checked=\"checked\">{$list_item}<br />";
  } else {
    echo "<input name=\"pdkAllergensInFacility[{$list_item_key}]\" type=\"checkbox\" value=\"{$list_item}\">{$list_item}<br />";
  }
}
?></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">May Contain</td>
        <td class="table_row">
          <textarea name="pdkMayContain" id="pdkMayContain" cols="45" rows="5"><?php echo $PDK_row->getField('pdkMayContain'); ?></textarea>
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
    
<!-- SKU IMAGERY SUBMISSION -->      
  <table id="SKU IMAGERY SUBMISSION" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="table_header" colspan="2">Supplier Package Sample</td>
    </tr>
    <tr>
      <td class="table_side_head_wrap"><p>Printed Sample – Representative printed, packaged product (including lid/cap options, etc.)<br />
        <br>
        <strong>Galileo Global Branding Group, Inc. would like to investigate any opportunities for unique and innovative packaging.&nbsp; Please provide any alternative, existing package options that could be utilized – e.g., optional label substrates, alternate bottle shapes.</strong></td>
      <td class="table_row_form"><p>Please send physical samples to</p>
        <p>Attn: <?php echo $PDK_row->getField('JobAccSPEC::accNameFML'); ?><br>
          Galileo Global Branding Group<br>
          700 Fairfield Avenue<br>
      Stamford, CT 06902</p></td>
    </tr>
  </table>
  
<!-- IMAGERY -->
  To add imagery, click on the + button in the IMAGERY field. This will give you the option of uploading new photographs or illustrations, or choosing from a list of imagery associated with previous jobs executed by Galileo Global Branding Group, Inc..
  <table id="IMAGERY" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="table_header" colspan=2>Photography/Imagery
	<!-- ADD BTN -->
	<?php
    // ASSIGN IMAGERY BUTTON
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
	foreach(fmsValueListItems($ContactLogin,'sup-PDK','Yes/No',"") as $list_item_key=>$list_item) {
	  if( fmsCompareSet($list_item, $PDK_row->getField('pdkExistingImagery')) ) {
	    echo "<input name=\"pdkExistingImagery\" type=\"radio\" value=\"{$list_item}\" checked=\"checked\">{$list_item}\n";
	  } else {
	    echo "<input name=\"pdkExistingImagery\" type=\"radio\" value=\"{$list_item}\">{$list_item}\n";
	  }
	}
	?></td>
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
          <input type="hidden" id="die_ids" value="<?php echo $UPC__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX'); ?>" />
          <input type="hidden" id="spec_ids" value="<?php echo $UPC__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX'); ?>" />
          <input type="hidden" id="printer_id" value="<?php echo $UPC__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK'); ?>" />
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

<form id="deassign_form" action="sku_deassign_response.php" method="post" enctype="multipart/form-data" name="deassign_form">
	<input type="hidden" id="-recid" name="-recid" value="<?php echo $UPC_row->getRecordId(); ?>" />
    <input type="hidden" id="u" name="u" value="<?php echo $UPC_row->getField('upc_PK'); ?>" />
    <input type="hidden" id="deassign_field" name="deassign_field" value="" />
	<input type="hidden" id="deassign_item" name="deassign_item" value="" />
	<input type="hidden" id="deassign_name" name="deassign_name" value="" />
</form>

</body>
</html>
