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

$Printers_find = $ContactLogin->newFindCommand('sup-Printer-assign');
$Printers_findCriterions = array('cmpStatus'=>'1','cmpVenCategory'=>'Printing',);
foreach($Printers_findCriterions as $key=>$value) {
    $Printers_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1);

$Printers_find->addSortRule('cmpCompany',1,FILEMAKER_SORT_ASCEND);

fmsSetPage($Printers_find,'Printers',2500);

fmsSetPage($Job_find,'Job',1);

$Supplier_result = $Supplier_find->execute();

$Job_result = $Job_find->execute();

$Printers_result = $Printers_find->execute();

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php");

fmsSetLastPage($Supplier_result,'Supplier',1);

fmsSetLastPage($Printers_result,'Printers',2500);

if(FileMaker::isError($Printers_result)) fmsTrapError($Printers_result,"error.php");

if(FileMaker::isError($Job_result)) fmsTrapError($Job_result,"error.php");

fmsSetLastPage($Job_result,'Job',1);

fmsSetLastPage($Supplier_result,'Supplier',1);

$Supplier_row = current($Supplier_result->getRecords());

$Job_row = current($Job_result->getRecords());

$Job__JobCmpSUPPLIER_portal = fmsRelatedRecord($Job_row, 'JobCmpSUPPLIER');
$Job__calc_portal = fmsRelatedRecord($Job_row, 'calc');
$Job__JobCmpCUSTOMER_portal = fmsRelatedRecord($Job_row, 'JobCmpCUSTOMER');
$Job__JobUpc_portal = fmsRelatedRecord($Job_row, 'JobUpc');
$Job__JobCmpPRINTER_portal = fmsRelatedRecord($Job_row, 'JobCmpPRINTER');


$Printers_row = current($Printers_result->getRecords());



// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php $job_FK_ven = $Job_row->getField('job_FK_ven'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak: <?php echo $Supplier_row->getField('cmpCompany'); ?></title>
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

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.liveFilter.js"></script>

<script type="text/javascript">

$(document).ready(function(){
	$('ul.live_filter').liveFilter('basic');
	$("#theList li").hover(
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
	$('#livefilter').css("background-color", "#FFFFCC").focus();
});

function setCheckboxDivs(){
	$(".checked").each(function() {
    	$(this).children("#itemUnchecked").hide();
		$(this).children("#itemChecked").show();
	});
}

function checkToggle(obj){
	AlreadyAssigned = $(obj).attr("name");
	if(AlreadyAssigned != "assigned"){
		$(obj).toggleClass("checked");
		$(obj).children("#itemUnchecked").toggle();
		$(obj).children("#itemChecked").toggle();
		gatherIDs();
	}
}

function gatherIDs(obj){
	var itemContainers ='';
	$(".checked").each(function() {
    	itemContainers += $(this).children("#item").attr("value") + '/';
	});
	$("#assigned_string").val(itemContainers);
}

function saveAssignments(){
	  document.printer_assign.submit();
}

$.fn.qtip.styles.mystyle = { // Last part is the name of the style
   width: 150,
   background: '#1ab7ea',
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
<!-- end #header -->

<body class="twoColHybLtHdr">


<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1>Assign Printer</h1>
  </div>

  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
    <li><a href="supplier.php">Update Company Contact Info</a></li>
	<li><a href="jobs.php">Jobs</a></li>
  </ul>

<h1>Add New</h1>
<ul id="add_new" class="MenuBarVertical">
  <li><a href="printer_new.php">Add New Printer</a></li>
</ul>

<h1>Actions</h1>
<ul id="actions" class="MenuBarVertical">
  <li><a onclick="saveAssignments()">Save</a></li>
  <li><a href="<?php echo $_SESSION['origin']; ?>">Back</a></li>
</ul>
<!-- end #sidebar1 -->
</div>

<!-- MAIN CONTENT -->
  <div id="mainContent">

  <?php
    if($_SESSION['conName'] == 'John Supplier'){
      echo "<h2>origin: <a href'" . $_SESSION['origin'] . "'>" . $_SESSION['origin'] . "</a></h2>";
    }
  ?>

    <h1>Assign Printer to Job: <?php echo $Job_row->getField('jobNameWbnd'); ?></h1>
    <p><span class="callout">INSTRUCTIONs:</span> Search for the printer of your choice using the search box. Click on the printer names, they will highlight in blue. When you are finished, click <em>Save</em> in the side menu or at the bottom of the page.<br>If the printer for this job does not show up in the search results, choose Add New Printer from the side menu.<br>
	<p><b>NOTE:</b> Once a printer is assigned it cannot be unassigned. If you need to delete a printer from the job, please contact Galileo Global Branding Group, Inc..</p>

    <div id="list_wrapper">

    <div id="searchBox">
		<div><input class="filter" name="livefilter" id="livefilter" type="text" value="" /> search</div>
    </div>

    <!-- FORM: PRINTER ASSIGN -->
    <form action="assign_printer_update.php" method="post" name="printer_assign" id="printer_assign">
        <input name="assigned_string" type="hidden" id="assigned_string" />
        <input name="-recid" type="hidden" value="<?php echo $Job_row->getRecordId(); ?>" id="-recid">
        <input name="job_FK_ven" type="hidden" value="<?php echo $Job_row->getField('job_FK_ven'); ?>" id="job_FK_ven">
		<input name="job_FK_vnc" type="hidden" value="<?php echo $Job_row->getField('job_FK_vnc'); ?>" id="job_FK_vnc">
    </form>
    <!-- HEADER DIV -->
    <div class="DivHeadToggle" id="Printers">Printers</div>

      <ul class="live_filter" id="theList">
      	<?php foreach($Printers_result->getRecords() as $Printers_row){
			$cmp_PK = $Printers_row->getField('cmp_PK');
			$cmp_PK_Formatted = "/\b" . $cmp_PK . "\b/";
			$filtered_PK = preg_match($cmp_PK_Formatted, $job_FK_ven);

			if ($filtered_PK != 1) {
				$class = "";
				$name = "";
			} else {
				$class = "class=\"checked\"";
				$name = "assigned";
			}

		?>
        <li id="itemContainer" <?php echo $class; ?> name="<?php echo $name; ?>" onClick="checkToggle(this)">
        	<div id="itemUnchecked"></div>
            <div id="itemChecked"></div>
            <div id="item" value="<?php echo $Printers_row->getField('cmp_PK'); ?>"><?php echo $Printers_row->getField('cmpCompany'); ?>
            </div>
        </li><?php } ?>
      </ul>

    </div>

	<input name="btn_Save" type="button" value="Save" id="btn_Save" onclick="saveAssignments()" class='grayButton' />

    <!-- end #mainContent --></div>
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
