<?php require_once('Connections/ContactLogin.php'); ?>

<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-Supplier');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

$UPC_find = $ContactLogin->newFindCommand('sup-UPC');
$UPC_findCriterions = array('upc_PK'=>$_REQUEST['u'],);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

$SKU_find = $ContactLogin->newFindCommand('sup-SKU');
$SKU_findCriterions = array('sku_PK'=>$_REQUEST['s'],);
foreach($SKU_findCriterions as $key=>$value) {
    $SKU_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1); 

fmsSetPage($SKU_find,'SKU',1); 

fmsSetPage($UPC_find,'UPC',1); 

$Supplier_result = $Supplier_find->execute(); 

$UPC_result = $UPC_find->execute(); 

$SKU_result = $SKU_find->execute(); 

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php"); 

if(FileMaker::isError($SKU_result)) fmsTrapError($SKU_result,"error.php"); 

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 

fmsSetLastPage($Supplier_result,'Supplier',1); 

fmsSetLastPage($SKU_result,'SKU',1); 

fmsSetLastPage($UPC_result,'UPC',1); 

$Supplier_row = current($Supplier_result->getRecords());

$Supplier__SupContact_portal = fmsRelatedRecord($Supplier_row, 'SupContact');
$Supplier__SupJobCmpPRINTER_portal = fmsRelatedRecord($Supplier_row, 'SupJobCmpPRINTER');
$Supplier__SupAddress_portal = fmsRelatedRecord($Supplier_row, 'SupAddress');
$Supplier__SupDie_portal = fmsRelatedRecord($Supplier_row, 'SupDie');
$Supplier__SupImg_portal = fmsRelatedRecord($Supplier_row, 'SupImg');
 

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
 

$SKU_row = current($SKU_result->getRecords());

$SKU__JobCmpSUPPLIER_portal = fmsRelatedRecord($SKU_row, 'JobCmpSUPPLIER');
$SKU__calc_portal = fmsRelatedRecord($SKU_row, 'calc');
$SKU__JobSkuSkdDie_portal = fmsRelatedRecord($SKU_row, 'JobSkuSkdDie');
$SKU__JobSkuSkiImg_portal = fmsRelatedRecord($SKU_row, 'JobSkuSkiImg');
$SKU__JobUpcDie_portal = fmsRelatedRecord($SKU_row, 'JobUpcDie');
$SKU__jobUpcImg_portal = fmsRelatedRecord($SKU_row, 'jobUpcImg');
$SKU__JobUpcSym_portal = fmsRelatedRecord($SKU_row, 'JobUpcSym');
$SKU__JobUpcPdkLci_portal = fmsRelatedRecord($SKU_row, 'JobUpcPdkLci');
 
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php // VARS
$pdk_FK_lci = $UPC_row->getField('JobUpcPdk::pdk_FK_lci');

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
<title>SpecTrak | Assign Label Code</title>
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

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip-1.0.0-rc3.min.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var uploadOrAssign = '';
var infoReturned = '<?php echo $_POST['infoReturned']; ?>';
var infoPassFail = '<?php echo $_POST['infoPassFail']; ?>';
var newItemName = '<?php echo $_POST['newItemName']; ?>';
var newItemDescription = '<?php echo $_POST['newItemDescription']; ?>';
var newItemFileName = '<?php echo $_POST['newItemFileName']; ?>';

$(document).ready(function(){
	var n = $("#theList #itemContainer").length;
	$("#historical_LCI").hide(); // just so it doesn't show on load
	switchToUpload();
	$("#new_lci_table #addBtn").hide();
	/*
	alert("length: " + n);
	if(n > 0){
		$("#new_LCI").hide();
	} else {
		$("#historical_LCI").hide();
	}
	*/
	$("#addBtn a").hover(function() {
		$(this).next("em").animate({opacity: "show", top: "-75"}, "slow");
	}, function() {
		$(this).next("em").animate({opacity: "hide", top: "-85"}, "fast");
	});
	
	$("#theList div").hover(
		function() {
			$(this).toggleClass("highlight");
		},
		function() {
			$(this).toggleClass("highlight");
		}
    );
	
	if(infoPassFail){
		if(infoPassFail == "pass"){
			switchToAssign();
		} else {
			switchToUpload();
		}
	}
	
	$("#itemContainer #itemChecked").hide();
	setCheckboxDivs();
	gatherIDs();
	toggleInfoPanel();
});

function toggleInfoPanel(){
	if (infoReturned){
		$("#infoPanel").text(infoReturned);
		//alert(newItemName);
		if(infoPassFail == "pass"){
			$("#infoPanel").addClass("pass");
			$("#theList #itemContainer").attr("name", "");
			$("#addBtn").hide();
			$("span#instructions").hide();
			$("input").hide();
			$("#new_item_name").text(newItemName);
			$("#new_item_description").text(newItemDescription);
			$("#new_item_file_name").text(newItemFileName);
			$("ul#actions li#save_item").hide();
		} else {
			$("#infoPanel").addClass("errorVar");
		}
		$("#infoPanel").fadeIn();
	}
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

function checkToggle(obj){
	$("#theList div").removeClass("checked");
	$(obj).toggleClass("checked");
	//alert($("#theList #itemContainer").length);
	if($("#theList #itemContainer").length > 1){
		$("#theList #itemChecked").hide();
		$("#theList #itemUnchecked").show();
		$(obj).children("#itemUnchecked").hide();
		$(obj).children("#itemChecked").show();
	}
	gatherIDs();
}

function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

function gatherIDs(obj){
	var lci ='';
	$(".checked").each(function() {
    	lci += $(this).attr("value");	
	});
	$("#assigned_string").val(lci);
}

function openDieline(url){
		window.open(url,'Download');
}

function switchToUpload(){
	uploadOrAssign = 'upload';
	$("#historical_LCI").fadeOut('fast', function() {
    	$("#new_LCI").fadeIn('fast');
		$("#instructionsAssign").hide();
  });
}

function switchToAssign(){
	uploadOrAssign = 'assign';
	$("#new_LCI").fadeOut('fast', function() {
    	$("#historical_LCI").fadeIn('fast');
		$("#instructionsAssign").show();
  });
}

function validateonsubmit(){
	var myForm=document.getElementById("upload_form");
	var SS= Spry.Widget.Form.validate(myForm);
	if(SS==true){
	myForm.submit();
	}
}

function saveBtn(){
	if(uploadOrAssign=='upload'){
		validateonsubmit();
	} else {
		document.assignment_form.submit();
	}
}

function linkOutAlert(url){
	if (infoReturned == ''){
		if (confirm('Leave without saving?')) { 
			window.location = url;
		}
	}
}
</script>

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
</head>

<body class="twoColHybLtHdr">


<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a onclick="linkOutAlert('index.php');"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1><?php echo $Supplier_row->getField('cmpCompany'); ?> Contact</h1>
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
        <li id="save_item"><a onclick="saveBtn()">Save</a></li>
        <li><a href="<?php echo $SKU_Detail; ?>">Back to SKU</a></li>
	</ul>

<!-- end #sidebar1 --></div>
 

<!-- MAIN CONTENT -->
  <div id="mainContent">
    <h1><?php echo $UPC_row->getField('JobCmpCUSTOMER::cmpCompany'); ?><br />
    <?php echo $UPC_row->getField('JOB::jobNameWbnd'); ?></h1>
    <span id="instructionsAssign"><span class="callout">Instruction:</span> Select any Additional Document you would like to assign by clicking on the name. It will highlight in blue. Click  <em>Save Assignments</em> in the side menu under Actions. Use the zoom button to view Label Code Image.</span>
    
<!-- INFO PANEL --> 
	<div id="infoPanel" class=""></div>
    
    <div id="historical_LCI">
    
    <form id="assignment_form" name="assignment_form" method="post" action="sku_assign_lci_update.php">
      
      <!-- PREVIOUS LABEL CODE IMAGES -->
      <!-- HEADER DIV -->
      <div class="DivHeadToggle">Previous Additional Documents for SKU
		<!-- ADD BTN -->
            <ul id="addBtn">
              <li id=""><a onClick="switchToUpload()"></a></li>
            </ul>
            <!-- ADD BTN -->
	    <script>
                $("ul:last").qtip({ 
                    show: 'mouseover',
                    hide: 'mouseout',
                    content: 'Upload New Additional Document',
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
      
      <div id="theList" class="table_list">

      
            <?php
$SKU__JobUpcPdkLci_portal_rownum = 1;
foreach(fmsRelatedSet($SKU_row,'JobUpcPdkLci') as $SKU__JobUpcPdkLci_portal_row=>$SKU__JobUpcPdkLci_portal){ 

				$lci_PK = $SKU__JobUpcPdkLci_portal->getField('JobUpcPdkLci::_PrimeLciIDX');
				$lci_path = $SKU__JobUpcPdkLci_portal->getField('JobUpcPdkLci::lciPath');

				if($lci_PK == $pdk_FK_lci){
					$class = "class=\"checked\"";
				} else {
					$class = "";
				}

?>
              <div id="itemContainer" <?php echo $class; ?> value="<?php echo $lci_PK; ?>" onClick="checkToggle(this)">
                <div id="itemViewRight" onclick="openDieline('<?php echo $lci_path; ?>')" title="View Label Code Image"></div>
                <div id="itemUnchecked"></div>
            	<div id="itemChecked"></div>
                <div name="item" id="item"><?php echo $SKU__JobUpcPdkLci_portal->getField('JobUpcPdkLci::lciName'); ?></div> 
              </div>
              <?php if($SKU__JobUpcPdkLci_portal_rownum == 0) break; else $SKU__JobUpcPdkLci_portal_rownum++;
}//portal_end ?>
      </div><!-- END #theList -->
      
     	<input name="assigned_string" type="hidden" value="" size="45" id="assigned_string" />
        <input name="-recid" type="hidden" value="<?php echo $UPC_row->getRecordId(); ?>" id="-recid">
        <input name="sku" type="hidden" value="<?php echo $SKU_Detail; ?>" id="sku">
        <input type="submit" name="save_assignment" id="save_assignment" value="Save Assignment">
    </form><!-- END #assignment_form -->
      
     </div><!-- END #historical_LCI -->
    
<div id="new_LCI">
<!-- UPLOAD FORM -->
<form action="lci_new_response.php" method=post enctype="multipart/form-data" name=upload_form id="upload_form">

<table id="new_lci_table" class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="table_header" colspan="2">New Additional Document
    <?php if($SKU__JobUpcPdkLci_portal->getField('JobUpcPdkLci::_PrimeLciIDX') > 0){ ?>		
    <!-- ADD BTN -->
            <ul id="addBtn">
              <li><a onClick="switchToAssign()"></a></li>
            </ul>
            <!-- ADD BTN -->
		    <script>
                $("ul:last").qtip({ 
                    show: 'mouseover',
                    hide: 'mouseout',
                    content: 'Assign Existing Additional Document',
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
    <?php } ?>
    </td>
  </tr>
  <tr>
    <td class="table_side_head_req">Name</td>
    <td class="table_row"><span id="sprytextfield1">
      <input type="text" name="lciName" id="lciName">
      </span><span id="new_item_name"></span></td>
  </tr>
  <tr>
    <td class="table_side_head_req">Description</td>
    <td class="table_row"><span id="sprytextfield2">
      <input type="text" name="lciDescription" id="lciDescription">
      </span><span id="new_item_description"></span></td>
  </tr>
  <tr>
    <td class="table_side_head_req">Type</td>
    <td class="table_row"><span id="spryradio1">
      <label>
        <input type="radio" name="lciType" value="Label Code Image" id="lciType_0">
        Label Code Image</label>
      <!--<br>-->
      <label>
        <input type="radio" name="lciType" value="Reference PDF" id="lciType_1">
        Reference PDF</label>
      <!--<br>-->
      <span class="radioRequiredMsg">Please make a selection.</span></span></td>
  </tr>
  <tr>
    <td class="table_side_head_req">File</td>
    <td class="table_row"><input type="file" name="file" id="file"><span id="new_item_file_name"></span></td>
  </tr>
</table>
<input name="-recid" type="hidden" value="<?php echo $UPC_row->getRecordId(); ?>" id="-recid">
<input name="upc_PK" type="hidden" value="<?php echo $UPC_row->getField('upc_PK'); ?>" id="upc_PK">
<input name="job_PK" type="hidden" value="<?php echo $UPC_row->getField('upc_FK_job'); ?>" id="job_PK">
<input name="redirect" type="hidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="redirect">
<input tabindex="2" accesskey="l" type="hidden" name="cmdupload" value="Save" />
<input type="hidden" name="MAX_FILE_SIZE" value="6000000">
<input name="lci_FK_cmp" type="hidden" value="<?php echo $Supplier_row->getField('cmp_PK'); ?>" id="lci_FK_cmp">
<input name="pdk_FK_lci" type="hidden" value="<?php echo $UPC_row->getField('JobUpcPdk::pdk_FK_lci'); ?>" id="pdk_FK_lci">
<input name="upc_FK_job" type="hidden" value="<?php echo $UPC_row->getField('upc_FK_job'); ?>" id="upc_FK_job">
<input name="cmpCompany" type="hidden" value="<?php echo $Supplier_row->getField('cmpCompanyFolderName'); ?>" id="cmpCompany">
<input type="submit" name="new_lci" id="new_lci" value="Save New Image">
</form>
</div>

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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["blur"]});
var MenuBar1 = new Spry.Widget.MenuBar("supplier", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("actions", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
