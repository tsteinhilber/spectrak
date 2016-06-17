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
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
$Supplier__SupDie_portal = fmsRelatedRecord($Supplier_row, 'SupDie');
 

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
<title>SpecTrak | Add New Dieline</title>
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
var anyDies = '<?php echo $Supplier__SupDie_portal->getField('SupDie::_PrimeDieIDX'); ?>';
var infoAssigned_item = '<?php echo $_REQUEST['assigned_item']; ?>';
var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
var maxuploadFileSize = 30000000;
var maxuploadFileSizeEng = "30MB";
var newItemName = '<?php echo $_POST['newItemName']; ?>';
var newItemDescription = '<?php echo $_POST['newItemDescription']; ?>';
var newItemFileName = '<?php echo $_POST['newItemFileName']; ?>';
var upcID = '<?php echo $_REQUEST['u']; ?>';
var deleteResponse = '<?php echo $_REQUEST['del']; ?>';
var deleteAssignedText = 'This dieline is currently assigned to other SKUs and cannot be deleted. You must de-assign it from all SKUs before it can be deleted.';
var deleteResponseText = 'The dieline has been deleted';

$(document).ready(function(){
	api = $("ul[name='assignUpcBtn']").qtip("api");
	$("#infoPanel").hide();
	$("ul#actions li#add_another").hide();
	$("#item_upload_table").hide();
	$("#itemList div.hidden").hide();
	
	// CHECK FOR DELETION RESPONSE
	if(deleteResponse == 'a'){
		setTimeout(alert(deleteAssignedText), 100);
	} else if (deleteResponse == 'd'){
		setTimeout(alert(deleteResponseText), 100);
	}
	
	// CHECK FOR ANY DIELINES
	if(anyDies == ''){
		$("#noDieInst").show();
		$("#cmp_items").hide();
		$("#item_upload_table").show();
		$("ul[name='switchToListBtn']").hide();
		$("#DieDescription").focus();
		uploadOrAssign = 'upload';
	} else {
		$("#noDieInst").hide();
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
			// setTimeout("$('span#instructions').fadeIn('slow');",6000);
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
		$(obj).children("#itemUnchecked").toggle();
		$(obj).children("#itemChecked").toggle();
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
	$("#upcList #itemContainer").children("#itemChecked").hide();
	$("#upcList #itemContainer").children("#itemUnchecked").show();
	$("#upcList #itemContainer").each(function(){	
		upcIDlocal = $(this).attr('value');
		upcItems = $(this).attr('items');
		if(upcItems.match(itemID)){
			$(this).addClass('checked');
			$(this).children("#itemChecked").show();
			$(this).children("#itemUnchecked").hide();
		}
		if($(this).attr('name') == 'current') {
			$(this).addClass('checked');
			$(this).children("#itemChecked").show();
			$(this).children("#itemUnchecked").hide();
		} 
		if($(this).attr('name') == ''){
			$(this).addClass('disabled');
		}
	});
	gatherUpcIDs(obj);
	api.updateContent(tipCopy);
}

// SET JOB UPC PDK
function setJopUpcPK(){
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
	//$('#itemList #itemContainer[value="'+upcID+'"]').addClass('checked');
	api.updateContent(tipCopy);
	gatherUpcIDs();
	gatherItemIDs();
}

// SHOW FORM
function showForm(){
	uploadOrAssign = 'upload';
	$("#cmp_items").fadeOut('fast');
	$("#item_upload_table").fadeIn('slow');
	$("#DieDescription").focus();
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
		if(SS == false || !assignedCheckUPC) {
			errorLog = errorLog + "You must assign the new dieline to at least one SKU to complete assignment. Please enter valid data into the fields highlighted in red.";
		}
		if(errorLog){
			flashNotice("NOTICE: There were issues saving this dieline", errorLog);
			highlightErrors();
			if(erroruploadFileSize){
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
		if(assignedCheckItems != ''){
			$('#upload_and_assign').attr('action', 'dieline_assign_response.php');
			myForm.submit();
		} else {
			flashNotice("NOTICE: There were issues assigning this dieline", "You must select a dieline to assign.");
			highlightErrors();
		}
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

// DELETE ITEM
function deleteItem(item){
	var skus = $("#assigned_upc").val();
	var urlString = 'dieline_delete.php?item=' + item;
	if(skus == ''){
		//alert('not assigned: ' + urlString);
		window.location.href = urlString;
	} else if(skus == upcID + '/') {
		//alert('match: ' + urlString);
		window.location.href = urlString;
	} else {
		alert(deleteAssignedText);
	}
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
    <h1>Add New Dieline</h1>
  </div><!-- end #header -->
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
	<li><a onclick="linkOutAlert('index.php');">Home</a></li>
    <li><a onclick="linkOutAlert('<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>');">Logout</a></li>
	<li><a onclick="linkOutAlert('supplier.php');">Update Company Contact Info</a></li>
    <li><a onclick="linkOutAlert('jobs.php');">Jobs</a></li>
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
    
<!-- INSTRUCTIONS -->
    <span id="instructions">
    	<p><span class="callout">Instruction:</span> Select an existing dieline from the list below, it will highlight in blue. To add a new dieline, click on the + button.<br>Select the SKU or SKUs that use that dieline, they will also highlight in blue. When you are finished, click <em>Save</em> in the side menu or at the bottom of the page.<br><strong>NOTE:</strong> SKUs that have a status of &quot;Submitted&quot; or &quot;Complete&quot; cannot be edited. Contact Galileo Global Branding Group, Inc. if you need to access these SKUs.</p>
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
    <form action="dieline_new_response.php" method="post" enctype="multipart/form-data" id="upload_and_assign">
  
<!-- UPLOAD DETAIL TABLE -->
    <table id="item_upload_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="table_header">Dieline</td>
        <td class="table_header">
        <!-- ADD BTN -->
        <ul id="addBtn" name="switchToListBtn">
            <li onclick="showList()"><a></a></li>
        </ul>
        <script>
			$("ul:last").qtip({ 
				show: 'mouseover',
				hide: 'mouseout',
				content: 'Show <?php echo $Supplier_row->getField('cmpCompany'); ?> Dielines' ,
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
        <td class="table_side_head_req">Description</td>
        <td class="table_row"><span id="sprytextfield2">
          <input type="text" name="DieDescription" id="DieDescription">
          </span><span id="new_item_description"></span></td>
      </tr>
      <tr>
        <td class="table_side_head_req">Electronic Dieline<br />(Macintosh Adobe Illustrator format is preferred)<br /><br /><strong>MAXIMUM FILE SIZE: 30MB</strong></td>
        <td class="table_row myFile">
			<input name="MAX_FILE_SIZE" type="hidden" value="30000000">
			<input type="file" name="file" id="file">
		</td>
      </tr>
    </table>
    
    
<!-- COMPANY DIELINES -->
<div id="cmp_items">
	<!-- HEADER DIV -->
    <div class="DivHeadToggle"><?php echo $Supplier_row->getField('cmpCompany'); ?> Dielines
    	<!-- ADD BTN -->
        <ul id="addBtn" name="switchToNewBtn">
            <li onclick="showForm()"><a></a></li>
        </ul>
        <script>
			$("ul:last").qtip({ 
				show: 'mouseover',
				hide: 'mouseout',
				content: 'Add New Dieline' ,
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
    
    <!-- itemLIST -->
    <div id="itemList" class="table_list">
      <?php
$Supplier__SupDie_portal_rownum = 1;
foreach(fmsRelatedSet($Supplier_row,'SupDie') as $Supplier__SupDie_portal_row=>$Supplier__SupDie_portal){ 

$timestamp = explode(" ", $Supplier__SupDie_portal->getField('SupDie::DieZdevCreationTimestampAuto'));
$time = strtotime( $timestamp[0] );
$creationDate = date( 'F j, Y', $time );
$itemName = $Supplier__SupDie_portal->getField('SupDie::DieFileName');
$itemID = $Supplier__SupDie_portal->getField('SupDie::_PrimeDieIDX');
$active = $Supplier__SupDie_portal->getField('SupDie::DieActiveFlag');
if($active != 1){ $class = "hidden"; } else { $class = ""; }

?>
        <div id="itemContainer" value="<?php echo $Supplier__SupDie_portal->getField('SupDie::_PrimeDieIDX'); ?>" onClick="toggleITEM(this)" class="<? echo $class; ?>">
		  <div id="itemDeleteRight" title="Delete <? echo $itemName; ?>" onclick="setTimeout( 'deleteItem(<?php echo $itemID; ?>)', 200 )"></div>
          <div id="itemViewRight" title="View <? echo $itemName; ?>" onclick="openDieline('<?php echo $Supplier__SupDie_portal->getField('SupDie::DiePath'); ?>')"></div>
          <div id="itemUnchecked" name="<?php echo $nameEditable; ?>"></div>
          <div id="itemChecked" name="<?php echo $nameEditable; ?>"></div>
          <div id="itemNameLeft" name="item"><?php echo $Supplier__SupDie_portal->getField('SupDie::DieFileName'); ?></div>
          <div name="itemName" id="itemName"><?php echo $creationDate; ?></div>
        </div>
        <?php if($Supplier__SupDie_portal_rownum == 0) break; else $Supplier__SupDie_portal_rownum++;
}//portal_end ?>
    </div>
    <div id="noDieInst" class="table_row_instr"><?php echo $Supplier_row->getField('cmpCompany'); ?> has no Dielines yet</div>
</div>

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
		$u = $_REQUEST['u'];
		$newItem_PK = $_POST['newItemID'];
		$upc_FK_die = $Job__JobUpc_portal->getField('JobUpc::upc_FK_die');
		$item_PK_Formatted = "/\b" . $newItem_PK . "\b/";
		$filtered_PK = preg_match($item_PK_Formatted, $upc_FK_die);
		$items = "/" . str_replace("\n", "/", $Job__JobUpc_portal->getField('JobUpc::upc_FK_die') ) . "/";
		
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
	<div id="itemContainer" class="<?php echo $class; ?>" value="<?php echo $upc_PK; ?>" name="<?php echo $nameEditable; ?>" items="<?php echo $items; ?>" onClick="toggleUPC(this)">
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
  
  <input name="btn_Save" type="button" value="Save" id="btn_Save" onclick="saveAction()" class='grayButton' />
  
  <input name="assigned_upc" type="hidden" value="" id="assigned_upc">
  <input name="assigned_item" type="hidden" value="" id="assigned_item">
  <input name="job_upc_pk" type="hidden" value="" id="job_upc_pk">
  <input name="backToDetail" type="hidden" value="<?php echo $backToDetail; ?>" id="backToDetail">
  <input name="cmdupload" type="hidden" value="Save">
  
  <input name="die_FK_cmp" type="hidden" value="<?php echo $Supplier_row->getField('cmp_PK'); ?>" id="die_FK_cmp">
  <input name="cmpCompany" type="hidden" value="<?php echo $Supplier_row->getField('cmpCompanyFolderName'); ?>" id="cmpCompany">
  
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
