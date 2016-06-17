<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$UPC_find = $ContactLogin->newFindCommand('sup-UPC');
$UPC_findCriterions = array('upc_PK'=>$_REQUEST['u'],);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($UPC_find,'UPC',1); 

$Supplier_result = $Supplier_find->execute(); 

$UPC_result = $UPC_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($UPC_result,'UPC',1); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

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
$UPC__JobSkuSkiImg_portal = fmsRelatedRecord($UPC_row, 'JobSkuSkiImg');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php
// JOB
$Job_find = $ContactLogin->newFindCommand('sup-Job');
$Job_findCriterions = array('job_PK'=>'=='.fmsEscape($UPC_row->getField('upc_FK_job')),);
foreach($Job_findCriterions as $key=>$value) {
    $Job_find->AddFindCriterion($key,$value);
}
fmsSetPage($Job_find,'Job',1);
$Job_result = $Job_find->execute(); 
if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php");
fmsSetLastPage($Job_result,'Job',1);

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');

$job_PK = $Job_row->getField('job_PK');
?>

<?php
// FIND PRINTERS
$Printers_find = $ContactLogin->newFindCommand('sup-Printer');
$Printers_findCriterions = array('SupJob::job_PK'=>$job_PK,'cmpVenCategory'=>'Printing',);
foreach($Printers_findCriterions as $key=>$value) {
    $Printers_find->AddFindCriterion($key,$value);
}

fmsSetPage($Printers_find,'Printers',500); 

$Printers_result = $Printers_find->execute(); 

if(FileMaker::isError($Printers_result)) fmsTrapError($Printers_result,"printer_new.php?u=". $UPC_row->getField('upc_PK')); 

fmsSetLastPage($Printers_result,'Printers',500); 

$Printers_row = current($Printers_result->getRecords());

$Printers__calc_portal = fmsRelatedRecord($Printers_row, 'calc');
$Printers__Sup_portal = fmsRelatedRecord($Printers_row, 'Sup');
$Printers__SupJobCmpPRINTERCon_portal = fmsRelatedRecord($Printers_row, 'SupJobCmpPRINTERCon');
$Printers__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Printers_row, 'SupJobCmpPRINTERAdd');
$Printers__SupJobCmpPRINTERSpe_portal = fmsRelatedRecord($Printers_row, 'SupJobCmpPRINTERSpe');
?>

<?php
// Set PDK File
$PDK_type = $UPC_row->getField('JobUpcSku::skuPDKType');
if ($PDK_type == "Food") {
	$SKU_Detail = "sku_f";
} elseif ($PDK_type == "Non-Food") {
	$SKU_Detail = "sku_nf";
} elseif ($PDK_type == "HBC") {
	$SKU_Detail = "sku_hbc";
} else {
	$SKU_Detail = "sku_f";
}

$SKU_Detail = $SKU_Detail . ".php?u=" . $_REQUEST['u'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Assign Printer Spec</title>
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
<link href="SpryAssets/SpryMenuBar.js.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#theList div").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
	);
	
	$("#itemContainer #itemChecked").hide();
	setCheckboxDivs();
	gatherIDs();
});

function checkToggle(obj){
	$(obj).toggleClass("checked");
	$(obj).children("#itemUnchecked").toggle();
	$(obj).children("#itemChecked").toggle();
	gatherIDs();
}

function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

function gatherIDs(obj){
	var items ='';
	$(".checked").each(function() {
    	items += $(this).attr("value") + '/';	
	});
	$("#upc_FK_spe").val(items);
}

function saveAssignments(){
	  document.specs.submit();
}

function saveAssignmentsAll(){
	  document.specs.action = "sku_assign_spec_update_all.php";
	  document.specs.submit();
}

function newSpec(id, name){
	$("#p").val(id);
	$("#com").val(name);
	$("#spec_new").submit();
}

$.fn.qtip.styles.mystyle = { // Last part is the name of the style
   width: 150,
   background: '#294C95',
   color: 'white',
   textAlign: 'center',
   border: {
	  width: 1,
	  radius: 3,
	  color: '#EAEAEA'
   },
   tip: 'bottomRight',
   name: 'dark' // Inherit the rest of the attributes from the preset dark style
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
      Assign Printer Spec</h1>
  </div>

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
    
      <h1>Actions</h1>
      <ul id="sku_tasks" class="MenuBarVertical">
        <li><a onclick="saveAssignments()">Save Assignments</a></li>
        <li><a onclick="saveAssignmentsAll()">Save Assignments to all SKUs</a></li>
        <li><a href="<?php echo $SKU_Detail; ?>">Back to SKU</a></li>
	  </ul>

<!-- end #sidebar1 --></div>

  <div id="mainContent">
    <h1><?php echo $UPC_row->getField('JobCmpCUSTOMER::cmpCompany'); ?><br />
    <?php echo $UPC_row->getField('JOB::jobNameWbnd'); ?></h1>
    <h2><?php echo $UPC_row->getField('JobUpcSku::skuSize'); ?> | <?php echo $UPC_row->getField('JobUpcSku::skuName'); ?> | <?php echo $UPC_row->getField('JobUpcSku::skuConfiguration'); ?></h2>
    <p><span class="callout">Instructions:</span> Select the printer spec to be assigned by clicking on the name (they will highlight in blue). When you have selected all the printer specs to assign, click  <em>Save Assignments</em> in the side menu under Actions. <br>
      <span class="callout"><br />
      NOTE:</span> To assign the selected printer specs to all SKUs for the Job, click  <em>Save Assignments to all SKUs</em>.<br />
To add a new set of printer specifications, click the + button next to the printer name.</p>
<!-- CONTENT CONTAINER & FORM -->
    <div id="contentContainer">
      <form id="specs" name="specs" method="post" action="sku_assign_spec_update.php">
      
      <!-- HIDDEN FIELDS -->
        <input name="upc_FK_spe" type="hidden" id="upc_FK_spe">
        <input name="job_PK" type="hidden" value="<?php echo $UPC_row->getField('upc_FK_job'); ?>" id="job_PK">
		<input name="upc_PK" type="hidden" value="<?php echo $UPC_row->getField('upc_PK'); ?>" id="upc_PK">
        <input name="-recid" type="hidden" value="<?php echo $UPC_row->getRecordId(); ?>" id="-recid">
        <input name="sku" type="hidden" value="<?php echo $SKU_Detail; ?>" id="sku" size="50">
        
		<?php foreach($Printers_result->getRecords() as $Printers_row){ ?>
          
          <!-- HEADER DIV -->
          <div class="DivHeadToggle" onClick="toggleItems1(this)"><?php echo $Printers_row->getField('cmpCompany'); ?>
          <!-- ADD BTN -->
          <ul id="addBtn">
            <li><a onClick="newSpec('<?php echo $Printers_row->getField('cmp_PK'); ?>', '<?php echo $Printers_row->getField('cmpCompany'); ?>')"></a></li>
          </ul>
          <script>
				$("ul:last").qtip({ 
					show: 'mouseover',
					hide: 'mouseout',
					content: 'Create New Spec for <?php echo $Printers_row->getField('cmpCompany'); ?>',
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
          </div>
          <div class="table_list" id="theList">
            
            <!-- ITEM DIV -->
            <?php
			$Printers__SupJobCmpPRINTERSpe_portal_rownum = 1;
			foreach(fmsRelatedSet($Printers_row,'SupJobCmpPRINTERSpe') as $Printers__SupJobCmpPRINTERSpe_portal_row=>$Printers__SupJobCmpPRINTERSpe_portal){ 
			
					$upc_FK_spe = $UPC_row->getField('upc_FK_spe');
					$spe_PK = $Printers__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpe::_PrimeSpeIDX');
					$spe_PK_Formatted = "/" . $spe_PK . "/";
					$filtered_spePK = preg_match($spe_PK_Formatted, $upc_FK_spe);
					
					if ($filtered_spePK != 1) {
						$class = "";
					} else {
						$class = "class=\"checked\"";
					}
			?>
              <div id="itemContainer" <?php echo $class; ?> value="<?php echo $spe_PK; ?>" onClick="checkToggle(this)">
                  <div id="itemUnchecked"></div>
              	  <div id="itemChecked"></div>
                  <div id="item" name="item">
                    <table id="assignTable" width="98%" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="80%"><?php echo $Printers__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpe::speName'); ?></td>
                          <td width="20%"><?php echo $Printers__SupJobCmpPRINTERSpe_portal->getField('SupJobCmpPRINTERSpeCon::conNameFull'); ?></td>
                        </tr>
                    </table>
                  </div>
              </div>
              <?php if($Printers__SupJobCmpPRINTERSpe_portal_rownum == 0) break; else $Printers__SupJobCmpPRINTERSpe_portal_rownum++;
			}//portal_end ?>
			<!-- END ITEM DIV -->
            
          </div>
          <?php } ?>
      </form>
      <input name="btn_save" type="button" value="Save Assignments" id="btn_save" onclick="saveAssignments()" />
      <input name="btn_saveAll" type="button" value="Save Assignments to all SKUs" id="btn_saveAll" onclick="saveAssignmentsAll()" />
      
      <!-- SPEC_NEW FORM -->
      <form action="spec_new.php" method="post" enctype="application/x-www-form-urlencoded" name="spec_new" id="spec_new">
        <input name="com" type="hidden" value="" id="com" />
        <input name="p" type="hidden" value="" id="p">
      </form>
      
    </div><!-- END CONTENTCONTAINER -->
  </div><!-- END MAIN CONTENT -->
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
    </table><p>© Galileo Global Branding Group, Inc.</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("sku_tasks", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
