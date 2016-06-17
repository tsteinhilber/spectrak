<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Job_find = $ContactLogin->newFindCommand('sup-Job Detail');
$Job_findCriterions = array('job_PK'=>$_REQUEST['j'],);
foreach($Job_findCriterions as $key=>$value) {
    $Job_find->AddFindCriterion($key,$value);
}

fmsSetPage($Job_find,'Job',1);

$Job_result = $Job_find->execute();

if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php");

fmsSetLastPage($Job_result,'Job',1);

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__calc_portal = fmsRelatedRecord($Job_row, 'calc');
$Job__JobCmpCUSTOMER_portal = fmsRelatedRecord($Job_row, 'JobCmpCUSTOMER');
$Job__JobAccAE_portal = fmsRelatedRecord($Job_row, 'JobAccAE');
$Job__JobAccSBM_portal = fmsRelatedRecord($Job_row, 'JobAccSBM');
$Job__JobAccPDM_portal = fmsRelatedRecord($Job_row, 'JobAccPDM');
$Job__JobAccSPEC_portal = fmsRelatedRecord($Job_row, 'JobAccSPEC');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');
$Job__JobUpcSpe_portal = fmsRelatedRecord($Job_row, 'JobUpcSpe');
$Job__JobUpcDie_portal = fmsRelatedRecord($Job_row, 'JobUpcDie');
$Job__jobUpcImg_portal = fmsRelatedRecord($Job_row, 'jobUpcImg');
$Job__JobUpcSym_portal = fmsRelatedRecord($Job_row, 'JobUpcSym');
$Job__JobUpcDoc_portal = fmsRelatedRecord($Job_row, 'JobUpcDoc');


// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php
$job = $Job_row->getField('job_PK');

// Set origin session var
$_SESSION['origin'] = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$Job__JobUpc_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){
	$PDK_status_check = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');
	if($PDK_status_check != "Complete" && $PDK_status_check != "Submitted"){
		$PDK_status_check_result ++;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Job: <?php echo $Job_row->getField('jobNumberCalc'); ?></title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css">
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->
<link rel="icon" type="image/gif" href="img/favicon.gif">
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/job.js"></script>
<script type="text/javascript" src="js/application.js"></script>

</head>

<body class="twoColHybLtHdr">

<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1>Job Detail</h1>
  </div><!-- end #header -->

  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

  <? if ($PDK_status_check_result) { ?>
	<h1>Add New</h1>
	<ul id="actions" class="MenuBarVertical">
		<li><a href="assign_printer.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Printer</a></li>
		<?php if($Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>
			<li><a href="spec_assign.php?j=<?php echo $job; ?>">Printer Specification</a></li>
		<? } ?>
		<li><a href="dieline.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Dieline</a></li>
		<li><a href="document.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Document</a></li>
		<li><a href="imagery.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Imagery</a></li>
		<li><a href="symbols.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Symbol</a></li>
	</ul>
   <? } ?>

<!-- end #sidebar1 --></div>

<!-- MAIN CONTENT -->
  <div id="mainContent">

    <?php
      if($_SESSION['conName'] == 'John Supplier'){
        echo "<h2>origin: " . $_SESSION['origin'] . "</h2>";
      }
    ?>

    <h1><?php echo $Job_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> | <?php echo $Job_row->getField('jobNameWbnd'); ?> | <?php echo $Job_row->getField('jobNumberCalc'); ?></h1>
    <p>Any New, Active, or Incomplete SKUs listed below require your attention. If you need to edit a SKU marked as &quot;Submitted&quot; or &quot;Complete&quot;, please contact Galileo Global Branding Group, Inc..</p>
    <h2>Definition of SKU Status:</h2>
    <p>New = Item currently has no information.<br>
      Active = Item has partial information, but still needs to be submitted.<br>
      Submitted = Item information has been completed and is pending approval by Galileo Global Branding Group, Inc..<br>
   	  Complete = Item information has been completed and approved by Galileo Global Branding Group, Inc. to begin the design process.<br>
      Incomplete = Item information was submitted with incomplete or incorrect information.</p>

    <!-- JOB CONTACTS TABLE -->
    <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="4">Job Contacts</td>
      </tr>
      <tr>
        <td class="table_side_head">Specification Contact</td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccSPEC::accNameFL'); ?></td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccSPEC::accEmail'); ?></td>
        <td class="table_row"><?php echo $Job_row->getField('JobAccSPEC::accPhoneWork'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Job Status Contact</td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccPDM::accNameFL'); ?></td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccPDM::accEmail'); ?></td>
        <td class="table_row"><?php echo $Job_row->getField('JobAccPDM::accPhoneWork'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Cost Estimate Contact</td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccAE::accNameFL'); ?></td>
        <td class="table_row_borderR"><?php echo $Job_row->getField('JobAccAE::accEmail'); ?></td>
        <td class="table_row"><?php echo $Job_row->getField('JobAccAE::accPhoneWork'); ?></td>
      </tr>
    </table>

	<!-- SKU INSTRUCTIONS -->
	To add item information, first click on the SKU NAME to reach the SKU DETAIL page.  Scroll down and complete the ITEM INFORMATION fields.</span>

	<!-- SKU TABLE -->
    <table id="sku_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" width="150" colspan=5>SKU INFO</td>
      </tr>
      <tr>
        <td class="table_sub_head" width="150">Status</td>
        <td class="table_sub_head">SKU Name</td>
        <td class="table_sub_head">SKU Size</td>
        <td class="table_sub_head">UPC Code</td>
		<td class="table_sub_head"></td>
      </tr>

        <?php
$Job__JobUpc_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){

$UPC_id = $Job__JobUpc_portal->getField('JobUpc::upc_PK');
$PDK_status = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');

$PDK_status = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');
if ($PDK_status == "New") {
	$class = "pdk_status_new";
} elseif ($PDK_status == "Active") {
	$class = "pdk_status_active";
} elseif ($PDK_status == "Submitted") {
	$class = "pdk_status_submitted";
} elseif ($PDK_status == "Complete") {
	$class = "pdk_status_complete";
} elseif ($PDK_status == "Submitted Incomplete") {
	$class = "pdk_status_subinc";
} else {
	$class = "table_row";
}

// Set PDK File
$PDK_type = $Job__JobUpc_portal->getField('JobUpcSku::skuPDKType');
if ($PDK_type == "Food") {
	$SKU_Detail = "sku_f";
} elseif ($PDK_type == "Non-Food") {
	$SKU_Detail = "sku_nf";
} elseif ($PDK_type == "HBC") {
	$SKU_Detail = "sku_hbc";
} else {
	$SKU_Detail = "sku_f";
}

// Create Button for Submit
$ReadyToSubmit = $Job__JobUpc_portal->getField('JobUpcPdk::pdk_flag_submit');
$Submit_Btn = "";
if ($ReadyToSubmit == 1) {
	$Submit_Btn = "<button onClick=\"openURL('remote_pdk_submit.php?u=" . $UPC_id . "')\">Submit</button>";
} else {
	if ($PDK_status == "New" || $PDK_status == "Active" || $PDK_status == "Submitted Incomplete"){
		$Submit_Btn = "Incomplete";
	}
}

?>
      <tr class="<?php echo $class ?>" onmouseover="ChangeColor(this, true);" onmouseout="ChangeColor(this, false);">
          <td class="table_row_borderR" width="150" onClick="openURL('<?php echo $SKU_Detail; ?>.php?u=<?php echo $UPC_id; ?>')"><img src="img/Arrow1 Right.png" width="16" height="16" id="arrow" name="arrow" title="Go to SKU detail"><?php echo $PDK_status; ?></td>
          <td class="table_row_borderR" onClick="openURL('<?php echo $SKU_Detail; ?>.php?u=<?php echo $UPC_id; ?>')"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuName'); ?></td>
          <td class="table_row_borderR" onClick="openURL('<?php echo $SKU_Detail; ?>.php?u=<?php echo $UPC_id; ?>')"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuSize'); ?></td>
          <td class="table_row_borderR" onClick="openURL('<?php echo $SKU_Detail; ?>.php?u=<?php echo $UPC_id; ?>')"><?php echo $Job__JobUpc_portal->getField('JobUpc::upcCode'); ?></td>
		  <td class="table_row"><?php echo $Submit_Btn; ?></td>
      </tr>
          <?php if($Job__JobUpc_portal_rownum == 0) break; else $Job__JobUpc_portal_rownum++;
}//portal_end ?>

    </table>

<!-- PRINT INFO TABLE -->
    To add printer information, first click the + button in the PRINTERS ASSIGNED field to add the printer contact, then the + button in the PRINTER SPECS field to add printer specifications.
    <table id="printer_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="5">Print Info</td>
      </tr>
	  <tr>
	  	<td class="printInfoSub" colspan="5">Printers Assigned
		<? if ($PDK_status_check_result) { ?>
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('assign_printer.php?j=<?php echo $job; ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({
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
            </script>
			<? } ?>
		</td>
	  </tr>

      <?php if(!$Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="5">Please assign a Printer to this Job, then you may assign Printer Specs.</td>
      </tr>
      <? } ?>

      <?php
$Job__JobCmpPRINTER_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobCmpPRINTER') as $Job__JobCmpPRINTER_portal_row=>$Job__JobCmpPRINTER_portal){
?>
        <tr onClick="openURL('printer.php?p=<?php echo $Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK'); ?>')">
          <td class="table_row_borderR_arrow" colspan="5" title="Go to <?php echo $Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmpCompany'); ?>"><?php echo $Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmpCompany'); ?></td>
        </tr>
        <?php if($Job__JobCmpPRINTER_portal_rownum == 0) break; else $Job__JobCmpPRINTER_portal_rownum++;
}//portal_end ?>

		<?php if($Job__JobCmpPRINTER_portal->getField('JobCmpPRINTER::cmp_PK')){ ?>

		<tr>
		  <td class="printInfoSub">Printer Specifications</td>
			<td class="printInfoSub">&nbsp;</td>
			<td class="printInfoSub">&nbsp;</td>
			<td class="printInfoSub">&nbsp;</td>
			<td class="printInfoSub">
			<? if ($PDK_status_check_result) { ?>
			<!-- ADD BTN -->
			<ul id="addBtn">
				<li><a onClick="openURL('spec_assign.php?j=<?php echo $job; ?>')"></a></li>
			</ul>
			<script>
					$("ul:last").qtip({
						show: 'mouseover',
						hide: 'mouseout',
						content: 'Add New Printer Specification',
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

      <?php if(!$Job__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="5">No printer specs currently assigned to this job.</td>
      </tr>
      <? } ?>

      <?php
$Job__JobUpcSpe_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpcSpe') as $Job__JobUpcSpe_portal_row=>$Job__JobUpcSpe_portal){

		$spec = $Job__JobUpcSpe_portal->getField('JobUpcSpe::_PrimeSpeIDX');
		$spec_path = "spec.php?s=" . $spec;
?>
        <tr onClick="openURL('spec_assign.php?j=<?php echo $job; ?>&assigned_item=<?php echo $spec; ?>')">
          <td class="table_row_borderR_arrow" title="Assign Spec"><?php echo $Job__JobUpcSpe_portal->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?></td>
          <td class="table_row_borderR"><?php echo $Job__JobUpcSpe_portal->getField('JobUpcSpe::speName'); ?></td>
          <td class="table_row_borderR"><?php echo $Job__JobUpcSpe_portal->getField('JobUpcSpeConPRINTER::conNameFull'); ?></td>
          <td class="table_row_borderR"><?php echo $Job__JobUpcSpe_portal->getField('JobUpcSpeConPRINTER::conPhoneFull'); ?></td>
		  <td class="table_row" width="18"><a href="<?php echo $spec_path; ?>"><img src="img/Zoom In.png" width="16" height="16" border="0" title="View Specification"></a></td>
        </tr>
        <?php if($Job__JobUpcSpe_portal_rownum == 0) break; else $Job__JobUpcSpe_portal_rownum++;
}//portal_end ?>
	<?php } ?>
    </table>


<!-- DIELINE TABLE -->
    To add dielines, click on the + button in the DIELINES field. This will give you the option of uploading a new dieline, or choosing from a list of dielines associated with previous jobs.
    <table id="dieline_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Dielines
        <? if ($PDK_status_check_result) { ?>
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('dieline.php?j=<?php echo $job; ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({
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
            </script>
			<? } ?>
        </td>
      </tr>

      <?php if(!$Job__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="2">No dielines currently assigned to this job.</td>
      </tr>
      <? } ?>

      <?php
$Job__JobUpcDie_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpcDie') as $Job__JobUpcDie_portal_row=>$Job__JobUpcDie_portal){

$dieline = $Job__JobUpcDie_portal->getField('JobUpcDie::_PrimeDieIDX');
$dieline_path = $Job__JobUpcDie_portal->getField('JobUpcDie::DiePath');

?>
        <tr>
          <td class="table_row_borderR_arrow" onClick="openURL('dieline.php?j=<?php echo $job; ?>&assigned_item=<?php echo $dieline; ?>')" title="Assign Dieline"><?php echo $Job__JobUpcDie_portal->getField('JobUpcDie::DieFileName'); ?></td>
          <td class="table_row" width="18"><a href="<?php echo $dieline_path; ?>" target="_blank"><img src="img/Zoom In.png" width="16" height="16" border="0" title="View dieline"></a></td>
        </tr>
        <?php if($Job__JobUpcDie_portal_rownum == 0) break; else $Job__JobUpcDie_portal_rownum++;
}//portal_end ?>
    </table>

<!-- SYMBOL TABLE -->
    To add symbols, click on the + button in the SYMBOLS field. This will allow you to select from our symbol library or upload your own.
    <table id="symbol_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Symbols
        <? if ($PDK_status_check_result) { ?>
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('symbols.php?j=<?php echo $Job_row->getField('job_PK'); ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({
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
            </script>
			<? } ?>
        </td>
      </tr>

      <?php if(!$Job__JobUpcSym_portal->getField('JobUpcSym::_PrimeSymIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="2">No symbols currently assigned to this job.</td>
      </tr>
      <? } ?>

      <?php
$Job__JobUpcSym_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpcSym') as $Job__JobUpcSym_portal_row=>$Job__JobUpcSym_portal){

$symbol = $Job__JobUpcSym_portal->getField('JobUpcSym::_PrimeSymIDX');
$symbol_path = $Job__JobUpcSym_portal->getField('JobUpcSym::sym_thumbnail_path');

?>
        <tr>
          <td class="table_row_borderR" align="CENTER"><a href="<?php echo $symbol_path; ?>" target="_blank"><img alt="symbol image" src="<?php echo $symbol_path; ?>" height="15" id="sym_img" name="sym_img" border="0" title="View Symbol"></a></td>
          <td class="table_row" title="Assign Symbol" onClick="openURL('symbols.php?j=<?php echo $job; ?>&assigned_item=<?php echo $symbol; ?>')"><?php echo $Job__JobUpcSym_portal->getField('JobUpcSym::symCertifierName'); ?></td>
        </tr>
        <?php if($Job__JobUpcSym_portal_rownum == 0) break; else $Job__JobUpcSym_portal_rownum++;
}//portal_end ?>
    </table>

<!-- IMAGERY TABLE -->
To add imagery, click on the + button in the IMAGERY field. This will give you the option of uploading new photographs or illustrations, or choosing from a list of imagery associated with previous jobs executed by Galileo Global Branding Group, Inc..
<table id="imagery_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Imagery
        <? if ($PDK_status_check_result) { ?>
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('imagery.php?j=<?php echo $Job_row->getField('job_PK'); ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({
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
            </script>
			<? } ?>
        </td>
      </tr>

      <?php if(!$Job__jobUpcImg_portal->getField('jobUpcImg::_PrimeImgIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="2">No imagery currently assigned to this job.</td>
      </tr>
      <? } ?>

      <?php
$Job__jobUpcImg_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'jobUpcImg') as $Job__jobUpcImg_portal_row=>$Job__jobUpcImg_portal){

	$image = $Job__jobUpcImg_portal->getField('jobUpcImg::_PrimeImgIDX');
	$image_path = $Job__jobUpcImg_portal->getField('jobUpcImg::ImgPath');

?>
        <tr>
          <td class="table_row_borderR_arrow" title="Assign Imagery" onClick="openURL('imagery.php?j=<?php echo $job; ?>&assigned_item=<?php echo $image; ?>')"><?php echo $Job__jobUpcImg_portal->getField('jobUpcImg::ImgFileName'); ?></td>
          <td class="table_row" width="18"><a href="<?php echo $image_path; ?>" target="_blank"><img src="img/Zoom In.png" width="16" height="16" title="View Imagery"></a></td>
        </tr>
        <?php if($Job__jobUpcImg_portal_rownum == 0) break; else $Job__jobUpcImg_portal_rownum++;
}//portal_end ?>
    </table>

	<!-- DOCUMENTS TABLE -->
    To add documents, click on the + button in the DOCUMENTS field. This will allow you to select from our symbol library or upload your own.
    <table id="symbol_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header" colspan="2">Documents
		<? if ($PDK_status_check_result) { ?>
		<!-- ADD BTN -->
        <ul id="addBtn">
            <li><a onClick="openURL('document.php?j=<?php echo $Job_row->getField('job_PK'); ?>')"></a></li>
        </ul>
        <script>
				$("ul:last").qtip({
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
            </script>
			<? } ?>
		</td>
      </tr>

      <?php if(!$Job__JobUpcDoc_portal->getField('JobUpcDoc::_PrimeDocIDX')){ ?>
      <tr>
      	<td class="table_row_instr" colspan="2">No documents currently assigned to this job.</td>
      </tr>
      <? } ?>

      <?php
$Job__JobUpcDoc_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpcDoc') as $Job__JobUpcDoc_portal_row=>$Job__JobUpcDoc_portal){

$document = $Job__JobUpcDoc_portal->getField('JobUpcDoc::_PrimeDocIDX');
$document_path = $Job__JobUpcDoc_portal->getField('JobUpcDoc::docPath');

?>
        <tr>
          <td class="table_row_borderR_arrow" title="View Document" onClick="openURL('document.php?j=<?php echo $job; ?>&assigned_item=<?php echo $document; ?>')"><?php echo $Job__JobUpcDoc_portal->getField('JobUpcDoc::docFileName'); ?></td>
		  <td class="table_row" width="18"><a href="<?php echo $document_path; ?>" target="_blank"><img src="img/Zoom In.png" width="16" height="16" title="View Document"></a></td>
        </tr>
        <?php if($Job__JobUpcDoc_portal_rownum == 0) break; else $Job__JobUpcDoc_portal_rownum++;
}//portal_end ?>
    </table>

</div><!-- end #mainContent -->
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
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
