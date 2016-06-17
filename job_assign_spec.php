<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-SupPrinters');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Job_find = $ContactLogin->newFindCommand('sup-Job');
$Job_findCriterions = array('job_PK'=>$_REQUEST['j'],);
foreach($Job_findCriterions as $key=>$value) {
    $Job_find->AddFindCriterion($key,$value);
}

$Spec_find = $ContactLogin->newFindCommand('sup-Spec');
$Spec_findCriterions = array('_PrimeSpeIDX'=>$_REQUEST['s'],);
foreach($Spec_findCriterions as $key=>$value) {
    $Spec_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Spec_find,'Spec',1); 

fmsSetPage($Job_find,'Job',1); 

$Supplier_result = $Supplier_find->execute(); 

$Job_result = $Job_find->execute(); 

$Spec_result = $Spec_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

if(FileMaker::isError($Spec_result)) fmsTrapError($Spec_result,"error.php"); 

fmsSetLastPage($Spec_result,'Spec',1); 

if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php"); 

fmsSetLastPage($Job_result,'Job',1); 

fmsSetLastPage($Supplier_result,'Supplier',1);  

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
 

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__calc_portal = fmsRelatedRecord($Job_row, 'calc');
$Job__JobCmpCUSTOMER_portal = fmsRelatedRecord($Job_row, 'JobCmpCUSTOMER');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');
 

$Spec_row = current($Spec_result->getRecords());

$Spec__JobUpcSpeCmpPRINTER_portal = fmsRelatedRecord($Spec_row, 'JobUpcSpeCmpPRINTER');
$Spec__calc_portal = fmsRelatedRecord($Spec_row, 'calc');
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Assign Spec</title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->

<link rel="icon" type="image/gif" href="img/favicon.gif">
<link href="SpryAssets/SpryMenuBar.js.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>

<script type="text/javascript">
var allAssigned = 0;
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
	setJopUpcPK();
	$("#addBtn li#viewSpec a").css('background-image', 'url(img/edit.png)');
});

function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

function checkToggle(obj){
	var PDKstatus = $(obj).children("#itemPDKstatus").text();
	PDKstatus = jQuery.trim(PDKstatus);
	PDKstatusTF = (PDKstatus == "Submitted" || PDKstatus == "Complete") ? "false" : "true";
	//PDKstatusTF = (PDKstatus == "Complete") ? "false" : "true";
	//alert("status: " + PDKstatusTF);
	if(PDKstatusTF == "true"){
		$(obj).toggleClass("checked");
		$(obj).children("#itemUnchecked").toggle();
		$(obj).children("#itemChecked").toggle();
		gatherIDs();
	}
}

function setJopUpcPK(){
	var jobupcPK ='';
	$("#theList #itemContainer[name='editable']").each(function() {
    	jobupcPK += $(this).attr("value") + '/';	
	});
	$("#job_upc_pk").val(jobupcPK);
}

function gatherIDs(obj){
	var skus ='';
	$(".checked").each(function() {
    	skus += $(this).attr("value") + '/';	
	});
	$("#assigned_string").val(skus);
}

function openDieline(url){
		window.open(url,'Download');
}

function gotoSpec(){
		window.location = "spec.php?s=<?php echo $Spec_row->getField('_PrimeSpeIDX'); ?>";
}

function saveAssignments(){
	  document.sku_assignment.submit();
	}
	
function saveAssignmentsAll(){
  $("#theList #itemContainer[name='editable']").addClass("checked");
  $("#theList #itemUnchecked[name='editable']").hide();
  $("#theList #itemChecked[name='editable']").show();
  gatherIDs();
  document.sku_assignment.submit();
}

function assignAll(obj){
	var tipCopy = '';
	if(allAssigned == 0){
		$("#theList #itemContainer[name='editable']").addClass("checked");
		$("#theList #itemUnchecked[name='editable']").hide();
		$("#theList #itemChecked[name='editable']").show();
		allAssigned = 1;
		tipCopy = 'De-assign All';
	} else {
		$("#theList div[name='editable']").removeClass("checked");
		$("#theList #itemUnchecked[name='editable']").show();
		$("#theList #itemChecked[name='editable']").hide();
		allAssigned = 0;
		tipCopy = 'Assign to All';
	}
	gatherIDs();
	var api = $("ul[name='assignBtn']").qtip("api");
    api.updateContent(tipCopy);
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
      Job: <?php echo $Job_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> | <?php echo $Job_row->getField('jobNumberCalc'); ?></h1>
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
<ul id="actions" class="MenuBarVertical">
	<li><a onclick="saveAssignments()">Save Assignments</a></li>
	<li><a onclick="saveAssignmentsAll()">Save Assignments to all SKUs</a></li>
	<li><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back to Job</a></li>
</ul>
<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">
    <h1>Assign Spec to Job SKU</h1>
    <p><span class="callout">Instruction:</span> Assign the Printer Spec to SKUs by selecting them. They will highlight in blue. When you are finished, click the Save Assignments button in the side menu under Actions. Job SKUs that have a status of &quot;Submitted&quot; or &quot;Complete&quot; cannot be changed.</p>
    
<!-- DIELINE DETAIL TABLE -->
    <table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Spec</td>
        <td class="table_header">
        <!-- VIEW BTN -->
            <ul id="addBtn">
              <li id="viewSpec"><a onClick="gotoSpec()"></a></li>
            </ul>
            <!-- TOOLTIP -->
	    	<script>
                $("ul:last").qtip({ 
                    show: 'mouseover',
                    hide: 'mouseout',
                    content: 'View Spec',
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
      <tr>
        <td class="table_side_head">Name</td>
        <td class="table_row"><?php echo $Spec_row->getField('speName'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Printer</td>
        <td class="table_row"><?php echo $Spec_row->getField('JobUpcSpeCmpPRINTER::cmpCompany'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Contact</td>
        <td class="table_row"><?php echo $Spec_row->getField('JobUpcSpeConPRINTER::conNameFull'); ?></td>
      </tr>
      <tr>
        <td class="table_side_head">Phone</td>
        <td class="table_row"><?php echo $Spec_row->getField('JobUpcSpeConPRINTER::conPhoneFull'); ?></td>
      </tr>
    </table>
    
  <!-- HEADER DIV -->
  <div class="DivHeadToggle" id="Job_SKU">Job SKUs
    <!-- ADD BTN -->
    <ul id="addBtn" name="assignBtn">
        <li onclick="assignAll(this)"><a></a></li>
    </ul>
    <script>
            $("ul:last").qtip({ 
                show: 'mouseover',
                hide: 'mouseout',
                content: 'Assign to All' ,
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
  
  <!-- theLIST -->
  <div id="theList" class="table_list">

	<?php
        $Job__JobUpc_portal_rownum = 1;
        foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){ 
		
		$pdkStatus = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');
		//if ($pdkStatus == "Submitted") continue; 
		//if ($pdkStatus == "Complete") continue; 
		
			$upc_PK = $Job__JobUpc_portal->getField('JobUpc::upc_PK');
			$item = $Spec_row->getField('_PrimeSpeIDX');
			$upc_FK_spe = $Job__JobUpc_portal->getField('JobUpc::upc_FK_spe');
			
			$item_Formatted = "/\b" . $item . "\b/";
			$filtered_PK = preg_match($item_Formatted, $upc_FK_spe);

			if ($filtered_PK != 1) {
				$class = "";
			} else {
				$class = "class=\"checked\"";
			}
			
			if($pdkStatus == "Submitted" || $pdkStatus == "Complete") {
				$nameEditable = "";
			} else {
				$nameEditable = "editable";
			}
	?>
        <div id="itemContainer" <?php echo $class; ?> value="<?php echo $upc_PK; ?>" name="<?php echo $nameEditable; ?>" onClick="checkToggle(this)">
            <div id="itemUnchecked" name="<?php echo $nameEditable; ?>"></div>
            <div id="itemChecked" name="<?php echo $nameEditable; ?>"></div>
            <div id="itemPDKstatus">
                <?php echo $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus'); ?>
            </div>
            <div name="itemName" id="itemName">
                <?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuName'); ?>
            </div>
        </div>
        
    <?php if($Job__JobUpc_portal_rownum == 0) break; else $Job__JobUpc_portal_rownum++;
    }//portal_end ?>

  </div>
    <input name="btn_save" type="button" value="Save Assignments" id="btn_save" onclick="saveAssignments()" />
    <input name="btn_saveAll" type="button" value="Save Assignments to all SKUs" id="btn_saveAll" onclick="saveAssignmentsAll()" />
<!-- FORM --> 
    <form id="sku_assignment" name="sku_assignment" method="post" action="job_assign_spec_update.php">
      <input name="assigned_string" type="hidden" value="" size="45" id="assigned_string" />
      <input size="100" type="hidden" name="job_upc_pk" id="job_upc_pk" value="">
      <input name="s" type="hidden" value="<?php echo $_REQUEST['s']; ?>" id="s">
      <input name="job" type="hidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="job">
    </form>

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
