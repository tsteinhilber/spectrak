<?php require_once('Connections/ContactLogin.php'); ?>

<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-Supplier');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$Job_find = $ContactLogin->newFindCommand('sup-Job');
$Job_findCriterions = array('job_PK'=>$_REQUEST['j'],);
foreach($Job_findCriterions as $key=>$value) {
    $Job_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($Job_find,'Job',1); 

$Supplier_result = $Supplier_find->execute(); 

$Job_result = $Job_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php"); 

fmsSetLastPage($Job_result,'Job',1); 

fmsSetLastPage($Supplier_result,'Supplier',1);  

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupContact_portal = fmsRelatedRecord($Supplier_row, 'SupContact');
$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
$Supplier__SupDie_portal = fmsRelatedRecord($Supplier_row, 'SupDie');
$Supplier__SupImg_portal = fmsRelatedRecord($Supplier_row, 'SupImg');
 

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__calc_portal = fmsRelatedRecord($Job_row, 'calc');
$Job__JobCmpCUSTOMER_portal = fmsRelatedRecord($Job_row, 'JobCmpCUSTOMER');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php
$backToDetail = $_REQUEST['backToDetail'];

// set SKU info
$Job__JobUpc_portal_rownum = 1;
foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){ 
	  
	  $upc_PK = $Job__JobUpc_portal->getField('JobUpc::upc_PK');
	  $u = $_REQUEST['u'];
	  if ($upc_PK == $u) {
		  $skuName = $Job__JobUpc_portal->getField('JobUpcSku::skuName');
		  $skuSize = $Job__JobUpc_portal->getField('JobUpcSku::skuSize');
		  $upcPDKStatus = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');
		  $skuPDKType = $Job__JobUpc_portal->getField('JobUpcSku::skuPDKType');
		  $upcCode = $Job__JobUpc_portal->getField('JobUpc::upcCode');
		  $upcDateComplete = $Job__JobUpc_portal->getField('JobUpc::upcDateComplete');
		  if($upcDateComplete){
			  $upcDateComplete = strtotime( $upcDateComplete ); // convert to time
			  $upcDateComplete = date( 'F j, Y', $upcDateComplete ); // format date
		  }
	  }
	  
if($Job__JobUpc_portal_rownum == 0) break; else $Job__JobUpc_portal_rownum++;
}//portal_end

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak | Duplicate SKU Info</title>
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

<!-- JAVASCRIPT -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script type="text/javascript">
var api = '';
var tipCopy = '';
var uploadOrAssign = '';
var allAssigned = 0;
var infoAssigned_item = '<?php echo $_REQUEST['assigned_item']; ?>';
var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
var upcID = '<?php echo $_REQUEST['u']; ?>';


$(document).ready(function(){
	api = $("ul[name='assignUpcBtn']").qtip("api");
	$("#infoPanel").hide();
	$("ul#actions li#add_another").hide();
	$("#dup_upc").val(upcID);
	
	// CHECK FOR A UPC
	if(upcID){
		$("#SKU_info").show();
	} else {
		$("#SKU_info").hide();
	}
	if(infoPassFail != "pass"){
		$("#upcList div[name='editable'], #itemList div").hover(
			function() {
				$(this).toggleClass("highlight");
			},
			function() {
				$(this).toggleClass("highlight");
			}
		);
	}
	$("#itemContainer #itemChecked").hide();
	setCheckboxDivs();
	gatherUpcIDs();
	setJopUpcPK();
	showInfoPanel();
	// flashNotice("NOTICE: There were issues creating this dieline", "Filename already exists, Please rename the file and retry.");
});

// SHOW INFO PANEL
function showInfoPanel(){
	if (infoReturned || infoAssigned_item){
		showList();
		$('#itemList #itemContainer[value="'+infoAssigned_item+'"]').click();
		if(infoReturned){
			if(infoPassFail == "pass"){
				$("#infoPanel").addClass("pass");
				$("#infoPanel").text(infoReturned);
				$("#infoPanel").fadeIn();
			} else {
				flashNotice("NOTICE: New dieline creation failed", infoReturned);
			}
			$("span#instructions").hide();
			setTimeout("$('#infoPanel').slideUp('fast');",6000);
			setTimeout("$('span#instructions').fadeIn('slow');",6000);
		}
	}
}

// SET CHECKBOX DIVS
function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

// TOGGLE UPC
function toggleUPC(obj){
	var editable = $(obj).attr("name");
	var pdk_type = $(obj).attr("pdk_type");
	if (editable == 'editable') {
		$(obj).toggleClass("checked");
		$(obj).children("#itemUnchecked").toggle();
		$(obj).children("#itemChecked").toggle();
		gatherUpcIDs();
		$("#pdk_type").val(pdk_type);
	}
}

// GATHER UPC IDS
function gatherUpcIDs(obj){
	var skus ='';
	$("#upcList .checked").each(function() {
    	skus += $(this).attr("value") + '/';	
	});
	$("#assigned_upc").val(skus);
}

// SHOW UTIL
function showUtil(){
	tipCopy = 'Assign to All';
	allAssigned = 0;
	$("#upcList #itemContainer").removeClass("checked");
	$("#upcList #itemContainer[name='']").addClass("disabled");
	$("#upcList #itemContainer[name='current']").addClass("checked");
	$("#upcList #itemContainer[name='editable']").children("#itemChecked").hide();
	$("#upcList #itemContainer[name='editable']").children("#itemUnchecked").show();
	$("#itemList #itemContainer").removeClass("checked");
	$("#itemList #itemContainer").children("#itemChecked").hide();
	$("#itemList #itemContainer").children("#itemUnchecked").show();
	//$('#itemList #itemContainer[value="'+upcID+'"]').addClass('checked');
	api.updateContent(tipCopy);
	gatherUpcIDs();
}

// SAVE ACTION
function saveAction(){
	var assignedCheckUPC = $("#assigned_upc").val();
	var dup_upc = $("#dup_upc").val();
	var myForm=document.getElementById("pdk_duplication");
	var SS = Spry.Widget.Form.validate(myForm);
	var script_param = dup_upc + ' ; ' + assignedCheckUPC
	$("#script_param").val(script_param);
	
	// ERROR CHECK ASSIGNMENT FORM
	if(assignedCheckUPC != ''){
		$('#pdk_duplication').attr('action', 'pdk_duplication_response.php');
		myForm.submit();
	} else {
		flashNotice("NOTICE: There were issues duplicating this SKU", "You must select at least one SKU from the list to duplicate.");
		highlightErrors();
	}
}

// SAVE ASSIGNMENT TO ALL
function saveAssignmentsAll(){
  $("#upcList #itemContainer[name='editable']").addClass("checked");
  $("#upcList #itemUnchecked[name='editable']").hide();
  $("#upcList #itemChecked[name='editable']").show();
  gatherUpcIDs();
  document.upload_and_assign.submit();
}

// ASSIGN TO ALL
function assignAll(obj){
	if(allAssigned == 0){
		$("#upcList #itemContainer[name='editable']").addClass("checked");
		$("#upcList #itemUnchecked[name='editable']").hide();
		$("#upcList #itemChecked[name='editable']").show();
		allAssigned = 1;
		tipCopy = 'De-assign All';
	} else {
		$("#upcList div[name='editable']").removeClass("checked");
		$("#upcList #itemUnchecked[name='editable']").show();
		$("#upcList #itemChecked[name='editable']").hide();
		allAssigned = 0;
		tipCopy = 'Assign to All';
	}
	gatherUpcIDs();
	var api = $("ul[name='assignUpcBtn']").qtip("api");
    api.updateContent(tipCopy);
}

</script>

</head>

<body class="twoColHybLtHdr">

<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a onclick="linkOutAlert('index.php');"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1>Duplicate SKU Info</h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a onclick="linkOutAlert('index.php');">Home</a></li>
    <li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>');">Logout</a></li>
	<li><a onclick="linkOutAlert('supplier.php');">Update Company Contact Info</a></li>
    <li><a onclick="linkOutAlert('jobs.php');">Jobs</a></li>
    <li><a onclick="linkOutAlert('sku_list.php');">SKU List</a></li>
  </ul>

<h1>printers</h1>
    <ul id="printers" class="MenuBarVertical">
    <?php
$Supplier__SupJobCmpPRINTER_portal_rownum = 1;
foreach(fmsRelatedSet($Supplier_row,'SupJobCmpPRINTER') as $Supplier__SupJobCmpPRINTER_portal_row=>$Supplier__SupJobCmpPRINTER_portal){ 
?>
      <li><a onclick="linkOutAlert('printer.php?p=<?php echo $Supplier__SupJobCmpPRINTER_portal->getField('SupJobCmpPRINTER::cmp_PK'); ?>');"><?php echo $Supplier__SupJobCmpPRINTER_portal->getField('SupJobCmpPRINTER::cmpCompany'); ?></a></li>
	<?php if($Supplier__SupJobCmpPRINTER_portal_rownum == 0) break; else $Supplier__SupJobCmpPRINTER_portal_rownum++;
}//portal_end ?>
</ul>

<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">
	<li id="save_item"><a onclick="saveAction()">Save</a></li>
    <li id="add_another"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>">Add Another Dieline</a></li>
	<li><a href="job.php?j=<?php echo $Job_row->getField('job_PK'); ?>">Back to Job</a></li>
    <?php if($_REQUEST['u']){ ?>
    <li><a href="<?php echo $backToDetail; ?>">Back to SKU</a></li>
    <?php } ?>
</ul>
<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">

<!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>

    <h1><?php echo $Job_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> | <?php echo $Job_row->getField('jobNameWbnd'); ?> | <?php echo $Job_row->getField('jobNumberCalc'); ?></h1>
    <span id="instructions"><span class="callout">Instruction:</span> Duplicate this SKU by selecting Job SKUs to accept this SKUs information. They will highlight in blue. When you are finished, click  <em>Save</em>  in the side menu under Actions. Job SKUs that have a status of &quot;Submitted&quot; or &quot;Complete&quot; cannot be changed.</span>
      
<!-- SKU INFO -->
    <table id="SKU_info" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">sku</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
        <td class="table_header">&nbsp;</td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Name</td>
        <td class="table_row" width="35%"><?php echo $skuName; ?></td>
        <td class="table_side_head_wrap"> Status</td>
        <td class="table_row" width="35%"><?php echo $upcPDKStatus; ?></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">Size</td>
        <td class="table_row" width="35%"><?php echo $skuSize; ?></td>
        <td class="table_side_head_wrap"> Type</td>
        <td class="table_row" width="35%"><?php echo $skuPDKType; ?></td>
      </tr>
      <tr>
        <td class="table_side_head_wrap">UPC</td>
        <td class="table_row" width="35%"><?php echo $upcCode; ?></td>
        <td class="table_side_head_wrap">Date Completed</td>
        <td class="table_row" width="35%"><?php echo $upcDateComplete; ?></td>
      </tr>
    </table>
    
<!-- FORM --> 
    <form action="pdk_duplication_response.php" method="post" enctype="multipart/form-data" name="pdk_duplication" id="pdk_duplication">

  <!-- HEADER DIV -->
  <div class="DivHeadToggle" id="Job_SKU">Job SKUs
    <!-- ADD BTN -->
    <ul id="addBtn" name="assignUpcBtn">
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
  
  <!-- upcLIST -->
  <div id="upcList" class="table_list">

	<?php
        $Job__JobUpc_portal_rownum = 1;
        foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){ 
		
		$pdkStatus = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');	
		$upc_PK = $Job__JobUpc_portal->getField('JobUpc::upc_PK');
		$pdk_type = $Job__JobUpc_portal->getField('JobUpcSku::skuPDKType');
		$u = $_REQUEST['u'];
		$newItem_PK = $_POST['newItemID'];
		$upc_FK_die = $Job__JobUpc_portal->getField('JobUpc::upc_FK_die');
		$item_PK_Formatted = "/\b" . $newItem_PK . "\b/";
		$filtered_PK = preg_match($item_PK_Formatted, $upc_FK_die);
		$items = "/" . str_replace("\n", "/", $Job__JobUpc_portal->getField('JobUpc::upc_FK_die') ) . "/";
		
		switch ($pdk_type) {
			case "Food":
				$pdk_type_ab = "f";
				break;
			case "Non-Food":
				$pdk_type_ab = "nf";
				break;
			case "HBC":
				$pdk_type_ab = "hbc";
				break;
		}
		
		if($pdkStatus == "Submitted" || $pdkStatus == "Complete") {
			$nameEditable = "";
		} elseif($upc_PK == $u) {
			$nameEditable = "current";
		} else {
			$nameEditable = "editable";
		}
		
		if ($upc_PK == $u) {
			$class = "disabled";
		} elseif ($filtered_PK == 1 && $newItem_PK != "") {
			$class = "checked";
		} elseif ($pdkStatus == "Submitted" || $pdkStatus == "Complete") {
			$class = "disabled";
		} else {
			$class = "";
		} 
		
	?>
	<div id="itemContainer" class="<?php echo $class; ?>" value="<?php echo $upc_PK; ?>" pdk_type="<?php echo $pdk_type_ab; ?>" name="<?php echo $nameEditable; ?>" items="<?php echo $items; ?>" onClick="toggleUPC(this)">
            <div id="itemUnchecked" name="<?php echo $nameEditable; ?>"></div>
            <div id="itemChecked" name="<?php echo $nameEditable; ?>"></div>
            <div id="itemPDKstatus"><?php echo $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus'); ?></div>
            <div name="itemName" id="itemCell"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuName'); ?>&nbsp;</div>
            <div name="itemSize" id="itemSkuSize"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuSize'); ?>&nbsp;</div>
            <div name="itemCode" id="itemName"><?php echo $Job__JobUpc_portal->getField('JobUpc::upcCode'); ?>&nbsp;</div>
	</div>
        
    <?php if($Job__JobUpc_portal_rownum == 0) break; else $Job__JobUpc_portal_rownum++;
    }//portal_end ?>

  </div>
  
  <input name="btn_Save" type="button" value="Save" id="btn_Save" onclick="saveAction()" />
  
  <input name="assigned_upc" type="hidden" value="" size="70" id="assigned_upc">
  <input name="dup_upc" type="hidden" value="" size="15" id="dup_upc">
  <input name="pdk_type" type="hidden" value="" size="15" id="pdk_type">
  <input name="backToDetail" type="hidden" size="45" value="<?php echo $backToDetail; ?>" id="backToDetail">
  
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
<script type="text/javascript">
	//alert("<?php echo $filtered_PK; ?>");
</script>
</body>
</html>
