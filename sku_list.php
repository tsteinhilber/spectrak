<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Jobs_find = $ContactLogin->newFindCommand('sup-Job');
$Jobs_findCriterions = array('job_spectrakFlag'=>'>='.fmsEscape('1'),'JobCmpSUPPLIER::cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Jobs_findCriterions as $key=>$value) {
    $Jobs_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Jobs_find,'Jobs',1); 

fmsSetPage($Jobs_find,'Jobs',100); 

$Supplier_result = $Supplier_find->execute(); 

$Jobs_result = $Jobs_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($Jobs_result,'Jobs',1); 

if(FileMaker::isError($Jobs_result)) fmsTrapError($Jobs_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1);  

fmsSetLastPage($Jobs_result,'Jobs',100); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

$Jobs_row = current($Jobs_result->getRecords());

$Jobs__JobCmpSUPPLIER_portal = fmsRelatedRecord($Jobs_row, 'JobCmpSUPPLIER');
$Jobs__calc_portal = fmsRelatedRecord($Jobs_row, 'calc');
$Jobs__JobCmpCUSTOMER_portal = fmsRelatedRecord($Jobs_row, 'JobCmpCUSTOMER');
$Jobs__JobUpc_portal = fmsRelatedRecord($Jobs_row, 'JobUpc');
$Jobs__JobCmpPRINTER_portal = fmsRelatedRecord($Jobs_row, 'JobCmpPRINTER');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | SKU list: <?php echo $Supplier_row->getField('cmpCompany'); ?></title>
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

<!-- JAVASCRIPT -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script type="text/javascript" src="SpryAssets/SpryMenuBar.js"></script>

<script type="text/javascript">
	
	function ChangeColor(tableRow, highLight){
		if (highLight){
		  tableRow.style.backgroundColor = '#E6E6E6';
		  tableRow.style.cursor = 'pointer';
		} else {
		  tableRow.style.backgroundColor = '';
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
    <h1>SKU List</h1>
  <!-- end #header --></div>
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
    <li><a href="sku_list.php">SKU List</a></li>
  </ul>

<h1>printers</h1>
    <ul id="printers" class="MenuBarVertical">
    <?php
$Supplier__SupJobCmpPRINTER_portal_rownum = 1;
foreach(fmsRelatedSet($Supplier_row,'SupJobCmpPRINTER') as $Supplier__SupJobCmpPRINTER_portal_row=>$Supplier__SupJobCmpPRINTER_portal){ 
?>
      <li><a href="printer.php?p=<?php echo $Supplier__SupJobCmpPRINTER_portal->getField('SupJobCmpPRINTER::cmp_PK'); ?>"><?php echo $Supplier__SupJobCmpPRINTER_portal->getField('SupJobCmpPRINTER::cmpCompany'); ?></a></li>
	<?php if($Supplier__SupJobCmpPRINTER_portal_rownum == 0) break; else $Supplier__SupJobCmpPRINTER_portal_rownum++;
}//portal_end ?>
</ul>

<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">
  <p><span class="callout">INSTRUCTIONS:</span> The jobs listed below are pending information before the design process can begin.  Please click on an individual item to add information.  Once the item information is complete, click the “Submit Item Information” button from the menu on the left.</p>
  <h2>Definition of SKU Status:    </h2>
  <p>New = Item currently has no information.<br />
    Active = Item has partial information, but still needs to be submitted.
      <br />
      Submitted = Item information has been completed and is pending approval by Galileo Global Branding Group, Inc..
      <br />
      Complete = Item information has been completed and approved by Galileo Global Branding Group, Inc. to begin the design process.
      <br />
    Incomplete = Item information was submitted with incomplete or incorrect information.</p>
<?php foreach($Jobs_result->getRecords() as $Jobs_row){ ?>
      <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th class="table_header" colspan="4"><?php echo $Jobs_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobNameWbnd'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobNumberCalc'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobSKUcount'); ?> SKUs</th>
        </tr>
      </thead>
      <tbody>
        <?php
$Jobs__JobUpc_portal_rownum = 1;
foreach(fmsRelatedSet($Jobs_row,'JobUpc') as $Jobs__JobUpc_portal_row=>$Jobs__JobUpc_portal){ 

	// Set PDK Status
	$PDK_status = $Jobs__JobUpc_portal->getField('JobUpc::upcPDKStatus');
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
	$PDK_type = $Jobs__JobUpc_portal->getField('JobUpcSku::skuPDKType');
	if ($PDK_type == "Food") {
		$SKU_Detail = "sku_f";
	} elseif ($PDK_type == "Non-Food") {
		$SKU_Detail = "sku_nf";
	} elseif ($PDK_type == "HBC") {
		$SKU_Detail = "sku_hbc";
	} else {
		$SKU_Detail = "sku_f";
	}

?>
          <tr 	class="<?php echo $class; ?>" 
          		onmouseover="ChangeColor(this, true);" 
              	onmouseout="ChangeColor(this, false);" 
              	onClick="openURL('<?php echo $SKU_Detail; ?>.php?u=<?php echo $Jobs__JobUpc_portal->getField('JobUpc::upc_PK'); ?>')">
            <td class="table_row_borderR_arrow" width="150" nowrap="nowrap"><?php echo $Jobs__JobUpc_portal->getField('JobUpc::upcPDKStatus'); ?></td>
            <td class="table_row_borderR"><?php echo $Jobs__JobUpc_portal->getField('JobUpcSku::skuName'); ?></td>
            <td class="table_row_borderR" width="100"><?php echo $Jobs__JobUpc_portal->getField('JobUpcSku::skuSize'); ?></td>
            <td class="table_row" width="150" nowrap="nowrap"><?php echo $Jobs__JobUpc_portal->getField('JobUpc::upcCode'); ?></td>
            </tr>
          <?php if($Jobs__JobUpc_portal_rownum == 0) break; else $Jobs__JobUpc_portal_rownum++;
}//portal_end ?>
		</tbody>
      </table>
      <?php } ?>
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
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
