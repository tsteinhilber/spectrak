<?php 
require_once('Connections/ContactLogin.php'); 
error_reporting(0);
?>

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

$Symbols_find = $ContactLogin->newFindCommand('sup-Symbol-List');
$Symbols_findCriterions = array('_PrimeSymIDX'=>'*','sym_active_flag'=>'1',);
foreach($Symbols_findCriterions as $key=>$value) {
    $Symbols_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

$Symbols_find->addSortRule('symCategory',1,FILEMAKER_SORT_ASCEND); 

$Symbols_find->addSortRule('symCertifierName',2,FILEMAKER_SORT_ASCEND); 

fmsSetPage($Symbols_find,'Symbols',1000); 

fmsSetPage($Job_find,'Job',1); 

$Supplier_result = $Supplier_find->execute(); 

$Job_result = $Job_find->execute(); 

$Symbols_result = $Symbols_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($Symbols_result)) fmsTrapError($Symbols_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($Symbols_result,'Symbols',1000); 

if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php"); 

fmsSetLastPage($Job_result,'Job',1); 

fmsSetLastPage($Supplier_result,'Supplier',1);  

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupContact_portal = fmsRelatedRecord($Supplier_row, 'SupContact');
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
 

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__calc_portal = fmsRelatedRecord($Job_row, 'calc');
$Job__JobCmpCUSTOMER_portal = fmsRelatedRecord($Job_row, 'JobCmpCUSTOMER');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');
 

$Symbols_row = current($Symbols_result->getRecords());

$Symbols__JOB_portal = fmsRelatedRecord($Symbols_row, 'JOB');
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php

$layoutSym = $ContactLogin->getLayout('sup-Symbol');
$symCategory = $layoutSym->getValueListTwoFields("symCategory");
$symType = $layoutSym->getValueListTwoFields("symType");

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
<title>SpecTrak | Add New Symbol</title>
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
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type="text/javascript" src="js/jquery.textchildren.js"></script>
<script type="text/javascript" src="js/arrayUnique.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script type="text/javascript">
var api = '';
var tipCopy = '';
var uploadOrAssign = '';
var allAssigned = 0;
var anyItems = '<?php echo $Symbols_row->getField('_PrimeSymIDX'); ?>';
var infoAssigned_item = '<?php echo $_REQUEST['assigned_item']; ?>';
var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
var maxFileSize = 30000000;
var maxFileSizeEng = "30MB";
var newItemFileName = '<?php echo $_POST['newItemFileName']; ?>';
var upcID = '<?php echo $_REQUEST['u']; ?>';
var itemCategories = '';

$(document).ready(function(){
	api = $("ul[name='assignUpcBtn']").qtip("api");
	$("#infoPanel").hide();
	$("#item_upload_table").hide();
	
	// CHECK FOR ANY ITEMS
	if(anyItems == ''){
		$("#noItemInst").show();
		$("#cmp_items").hide();
		$("#item_upload_table").show();
		$("ul[name='switchToListBtn']").hide();
		$("#ItemDescription").focus();
		uploadOrAssign = 'upload';
	} else {
		$("#noItemInst").hide();
		$("#cmp_items").show();
		uploadOrAssign = 'assign';
	}
	
	// CHECK FOR A UPC
	if(upcID){
		$("#SKU_info").show();
	} else {
		$("#SKU_info").hide();
	}
	if(infoPassFail != "pass"){
		$("#upcList tr[name='editable'], #itemList tr").hover(
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
	setJobUpcPK();
	showInfoPanel();
	createCategoryList();
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
				flashNotice("NOTICE: New symbol creation failed", infoReturned);
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
	if (editable == 'editable') {
		$(obj).toggleClass("checked");
		$(obj).find("#itemUnchecked").toggle();
		$(obj).find("#itemChecked").toggle();
		gatherUpcIDs();
	}
}

// TOGGLE ITEM
function toggleITEM(obj){
	var itemID = '/' + $(obj).attr('value') + '/';
	var upcItems = '';
	allAssigned = 0;
	tipCopy = 'Assign to All';
	$("#itemList #itemContainer").removeClass("checked");						// uncheck all items
	$("#itemList #itemContainer").children("#itemChecked").hide();
	$("#itemList #itemContainer").children("#itemUnchecked").show();
	$(obj).toggleClass("checked");												// toggle for obj item
	$(obj).children("#itemUnchecked").hide();
	$(obj).children("#itemChecked").show();
	gatherItemIDs();
	$("#upcList #itemContainer").removeClass();
	$("#upcList #itemChecked").hide();
	$("#upcList #itemUnchecked").show();
	$("#upcList #itemContainer").each(function(){	
		upcIDlocal = $(this).attr('value');
		upcItems = $(this).attr('items');
		if(upcItems.match(itemID)){
			$(this).addClass('checked');
			$(this).find("#itemChecked").show();
			$(this).find("#itemUnchecked").hide();
		}
		if($(this).attr('name') == 'current') {
			$(this).addClass('checked');
			$(this).find("#itemChecked").show();
			$(this).find("#itemUnchecked").hide();
		} 
		if($(this).attr('name') == ''){
			$(this).addClass('disabled');
		}
	});
	gatherUpcIDs(obj);
	api.updateContent(tipCopy);
}

// SET JOB UPC PK
function setJobUpcPK(){
	var jobupcPK ='';
	$("#upcList #itemContainer[name='editable'], #upcList #itemContainer[name='current']").each(function() {
    	jobupcPK += $(this).attr("value") + '/';	
	});
	$("#job_upc_pk").val(jobupcPK);
}

// GATHER UPC IDS
function gatherUpcIDs(obj){
	var skus ='';
	$("#upcList .checked").each(function() {
    	skus += $(this).attr("value") + '/';	
	});
	$("#assigned_upc").val(skus);
}

// GATHER ITEM IDS
function gatherItemIDs(obj){
	var itemID ='';
	$("#itemList .checked").each(function() {
    	itemID += $(this).attr("value");	
	});
	$("#assigned_item").val(itemID);
}

// OPEN DIELINE
function openDieline(url){
	window.open(url,'Download');
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
	api.updateContent(tipCopy);
	gatherUpcIDs();
	gatherItemIDs();
}

// SHOW FORM
function showForm(){
	uploadOrAssign = 'upload';
	$("#cmp_items").fadeOut('fast');
	$("#item_upload_table").fadeIn('slow');
	$("#ItemDescription").focus();
	showUtil();
}

// SHOW LIST
function showList(){
	uploadOrAssign = 'assign';
	$("#item_upload_table").fadeOut('fast');
	$("#cmp_items").fadeIn('slow');
	showUtil();
}

// SAVE ACTION
function saveAction(){
	var assignedCheckUPC = $("#assigned_upc").val();
	var assignedCheckItems = $("#assigned_item").val();
	var myForm=document.getElementById("upload_and_assign");
	var SS = Spry.Widget.Form.validate(myForm);
	var errorLog = "";
	
	// ERROR CHECK UPLOAD FORM
	if(uploadOrAssign == 'upload'){
		if(!$.browser.msie){
			var uploadFileSize = (document.getElementById("file").files.item(0)) ? document.getElementById("file").files.item(0).size : "";
			if(uploadFileSize > maxuploadFileSize){
				errorLog = "The file exceeds the allowed maximum of " + maxuploadFileSizeEng + ". Please contact Galilieo Global Branding Group, Inc. ";
			}
		}
		if(SS != true || assignedCheckUPC == '') {
			errorLog = errorLog + "You must assign the new dieline to at least one SKU to complete assignment. Please enter valid data into the fields highlighted in red.";
		}
		if(errorLog){
			flashNotice("NOTICE: There were issues saving this symbol", errorLog);
			highlightErrors();
			if(errorFileSize){
				$("#file").parent().css('background-color', '#FF99A8');
				$("#file").parent().siblings().css('background-color', '#FF99A8');
			}
		}
		if(assignedCheckUPC != '' && SS == true && errorLog == ""){
			myForm.submit();
		}
	}
	
	// ERROR CHECK ASSIGNMENT FORM
	if(uploadOrAssign == 'assign'){
		if(assignedCheckUPC != '' && assignedCheckItems != ''){
			$('#upload_and_assign').attr('action', 'symbol_assign_response.php');
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues assigning this symbol", "You must assign the selected symbol to at least one SKU to complete assignment.");
			highlightErrors();
		}
	}
}

// SAVE ASSIGNMENTS TO ALL
function saveAssignmentsAll(){
  $("#upcList #itemContainer[name='editable']").addClass("checked");
  $("#upcList #itemUnchecked[name='editable']").hide();
  $("#upcList #itemChecked[name='editable']").show();
  gatherUpcIDs();
  document.upload_and_assign.submit();
}

// ASSIGN ALL
function assignAll(obj){
	if(allAssigned == 0){
		$("#upcList #itemContainer[name='editable']").addClass("checked");
		$("#upcList #itemUnchecked[name='editable']").hide();
		$("#upcList #itemChecked[name='editable']").show();
		allAssigned = 1;
		tipCopy = 'De-assign All';
	} else {
		$("#upcList tr[name='editable']").removeClass("checked");
		$("#upcList #itemUnchecked[name='editable']").show();
		$("#upcList #itemChecked[name='editable']").hide();
		allAssigned = 0;
		tipCopy = 'Assign to All';
	}
	gatherUpcIDs();
	var api = $("ul[name='assignUpcBtn']").qtip("api");
    api.updateContent(tipCopy);
}

// CATEGORY FILTER
function categoryFilter(category){	
	if(category == "All"){
		$("#itemList #itemContainer").show();
	} else {
		$("#itemList #itemContainer").hide();
		$("#itemList #itemContainer").children("#itemCategory:contains(" + category + ")").parent().show();
	}
	$("#categoryDisplay").text(category);
	
}

// CREATE CATEGORY
function createCategoryList(){
	var categoriesString = '';
	itemCategories = $("#itemContainer #itemCategory").textChildren({outputType: 'array'});
	itemCategories = itemCategories.unique();
	
	var len=itemCategories.length;
	for(var i=0; i<len; i++) {
		var value = itemCategories[i];
		categoriesString = categoriesString + "<li><a onclick=\"categoryFilter('" + value + "')\">" + value + "</a></li>";
	}
	$("#categoryAll").after(categoriesString);
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
    <h1>Add New Symbol</h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

<h1>Symbol Categories</h1>
<ul id="categoryFilter" class="MenuBarVertical">
	<li id="categoryAll"><a onclick="categoryFilter('All')">All</a></li>
</ul>

<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">
	<li id="save_item"><a onclick="saveAction()">Save</a></li>
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

<!-- INSTRUCTIONS -->
    <span id="instructions">
    	<p><span class="callout">Instruction:</span> Select an existing symbol from the list below, it will highlight in blue. To add a new symbol, click on the + button.<br>Select the SKU or SKUs that use that symbol, they will also highlight in blue. When you are finished, click <em>Save</em> in the side menu or at the bottom of the page.<br><strong>NOTE:</strong> SKUs that have a status of &quot;Submitted&quot; or &quot;Complete&quot; cannot be edited. Contact Galileo Global Branding Group, Inc. if you need to access these SKUs.</p>
    </span> 
   
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
    <form action="symbol_new_response.php" method="post" enctype="multipart/form-data" name="upload_and_assign" id="upload_and_assign">
  
<!-- UPLOAD DETAIL TABLE -->
    <table id="item_upload_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Symbol</td>
        <td class="table_header">
        <!-- ADD BTN -->
        <ul id="addBtn" name="switchToListBtn">
            <li onclick="showList()"><a></a></li>
        </ul>
        <script>
			$("ul:last").qtip({ 
				show: 'mouseover',
				hide: 'mouseout',
				content: 'Show Symbols Library' ,
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
        <td class="table_side_head_req">Certifier Name</td>
        <td class="table_row"><span id="sprytextfield2">
          <input type="text" name="symCertifierName" id="symCertifierName">
          </span><span id="new_item_description"></span></td>
      </tr>
      <tr>
        <td class="table_side_head">Certifier Acronym</td>
        <td class="table_row"><span id="sprytextfield1">
          <input type="text" name="symCertifierAcronym" id="symCertifierAcronym">
          </span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Category</td>
        <td class="table_row">
				<?php
				foreach ($symCategory as $displayValue => $value) {
					echo "<input name=\"symCategory\" type=\"radio\" value=\"{$value}\">{$value}\n";
				}
				?>
	      </td>
      </tr>
      <tr>
        <td class="table_side_head">Type</td>
        <td class="table_row">
				<?php
				foreach ($symType as $displayValue => $value) {
					echo "<input name=\"symType\" type=\"radio\" value=\"{$value}\">{$value}\n";
				}
				?>
        </td>
      </tr>
      <tr>
        <td class="table_side_head_req">Image File (AI, PDF, EPS)<br /><br /><strong>MAXIMUM FILE SIZE: 30MB</strong></td>
        <td class="table_row">
			<input name="MAX_FILE_SIZE" type="hidden" value="30000000">
			<input type="file" name="file" id="file"><span id="new_item_file_name">
		</td>
      </tr>
    </table>
    
<!-- SYMBOL LIBRARY -->  
<div id="cmp_items">  
<table id="itemList" class="table" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="table_header" colspan="3">Symbol Library: <span id="categoryDisplay">All</span>
    <!-- ADD BTN -->
        <ul id="addBtn" name="switchToNewBtn">
            <li onclick="showForm()"><a></a></li>
        </ul>
        <script>
			$("ul:last").qtip({ 
				show: 'mouseover',
				hide: 'mouseout',
				content: 'Add New Symbol' ,
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
        </script></td>
  </tr>
  <?php foreach($Symbols_result->getRecords() as $Symbols_row){ ?>
  <tr id="itemContainer" value="<?php echo $Symbols_row->getField('_PrimeSymIDX'); ?>" onClick="toggleITEM(this)">
    <td class="table_row_borderR" align="CENTER" title="View Symbol" onclick="openDieline('<?php echo $Symbols_row->getField('symLowResPath'); ?>')" width="50"><img alt="symbol image" src="<?php echo $Symbols_row->getField('sym_thumbnail_path'); ?>" height="15" name="symbol"></td>
    <td class="table_row_borderR"><?php echo $Symbols_row->getField('symCertifierName'); ?></td>
    <td id="itemCategory" class="table_row"><?php echo $Symbols_row->getField('symCategory'); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td id="noItemInst" class="table_row_instr" colspan="3">Add first Symbol to library</td>
    </tr>
</table>

</div>

<!-- NEW UPC LIST -->
<table id="upcList" class="table" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="table_header" colspan="5">Job SKUs
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
    </td>
  </tr>
  
  <?php
        $Job__JobUpc_portal_rownum = 1;
        foreach(fmsRelatedSet($Job_row,'JobUpc') as $Job__JobUpc_portal_row=>$Job__JobUpc_portal){ 
		
		$pdkStatus = $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus');	
		$upc_PK = $Job__JobUpc_portal->getField('JobUpc::upc_PK');
		$u = $_REQUEST['u'];
		$newItem_PK = $_POST['newItemID'];
		$upc_FK_die = $Job__JobUpc_portal->getField('JobUpc::upc_FK_img');
		$item_PK_Formatted = "/\b" . $newItem_PK . "\b/";
		$filtered_PK = preg_match($item_PK_Formatted, $upc_FK_die);
		$items = "/" . str_replace("\n", "/", $Job__JobUpc_portal->getField('JobUpc::upc_FK_sym') ) . "/";
		
		if($pdkStatus == "Submitted" || $pdkStatus == "Complete") {
			$nameEditable = "";
		} elseif($upc_PK == $u) {
			$nameEditable = "current";
		} else {
			$nameEditable = "editable";
		}
		
		if ($upc_PK == $u) {
			$class = "checked";
		} elseif ($filtered_PK == 1 && $newItem_PK != "") {
			$class = "checked";
		} elseif ($pdkStatus == "Submitted" || $pdkStatus == "Complete") {
			$class = "disabled";
		} else {
			$class = "";
		} 
		
	?>
  
  <tr id="itemContainer" class="<?php echo $class; ?>" value="<?php echo $upc_PK; ?>" name="<?php echo $nameEditable; ?>" items="<?php echo $items; ?>" onClick="toggleUPC(this)">
    <td id="checkboxes" class="table_row" width="21"><div id="itemUnchecked" name="<?php echo $nameEditable; ?>"></div><div id="itemChecked" name="<?php echo $nameEditable; ?>"></div></td>
    <td class="table_row_borderR"><?php echo $Job__JobUpc_portal->getField('JobUpc::upcPDKStatus'); ?></td>
    <td class="table_row_borderR"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuName'); ?></td>
    <td class="table_row_borderR" width="60"><?php echo $Job__JobUpc_portal->getField('JobUpcSku::skuSize'); ?></td>
    <td class="table_row" width="20%" nowrap="nowrap"><?php echo $Job__JobUpc_portal->getField('JobUpc::upcCode'); ?></td>
  </tr>
  
  <?php if($Job__JobUpc_portal_rownum == 0) break; else $Job__JobUpc_portal_rownum++;
    }//portal_end ?>
  
</table>
  
  <input name="assigned_upc" type="hidden" value="" size="45" id="assigned_upc">
  <input name="assigned_item" type="hidden" value="" size="45" id="assigned_item">
  <input name="job_upc_pk" type="hidden" value="" id="job_upc_pk">
  <input name="backToDetail" type="hidden" size="45" value="<?php echo $backToDetail; ?>" id="backToDetail">
  <input name="cmdupload" type="hidden" value="Save">
  <input name="img_FK_cmp" type="hidden" value="<?php echo $Supplier_row->getField('cmp_PK'); ?>" id="img_FK_cmp">
  <input name="cmpCompany" type="hidden" value="<?php echo $Supplier_row->getField('cmpCompanyFolderName'); ?>" id="cmpCompany">
  <input type="button" name="symbol_submit" value="Save" onclick="saveAction()" class='grayButton' />
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["change"], isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["change"]});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {isRequired:false});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("categoryFilter", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar4 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
//-->
</script>
</body>
</html>
