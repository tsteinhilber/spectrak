<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Jobs_find = $ContactLogin->newFindCommand('sup-Job');
$Jobs_findCriterions = array('job_spectrakFlag'=>'>='.fmsEscape('1'),'JobCmpSUPPLIER::cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Jobs_findCriterions as $key=>$value) {
    $Jobs_find->AddFindCriterion($key,$value);
}

fmsSetPage($Jobs_find,'Jobs',100); 

$Jobs_result = $Jobs_find->execute(); 

if(FileMaker::isError($Jobs_result)) fmsTrapError($Jobs_result,"error.php"); 

fmsSetLastPage($Jobs_result,'Jobs',100); 

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
<title>SpecTrak Job list: <?php echo $_SESSION['supplierName']; ?></title>
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
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPT -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		
		$("#mainContent > div").hover(function() {
				$(this).toggleClass("table_highlight");
			},
		function() {
				$(this).toggleClass("table_highlight");
			}
		)
		
	});

  </script>

</head>

<body class="twoColHybLtHdr">

<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25"></a></li>
	  </ul>
	</div>
    <h1>Job List</h1>
  <!-- end #header --></div>
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="supplier" class="MenuBarVertical">
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">
  <p><span class="callout">INSTRUCTIONS:</span> All jobs currently open with Galileo Global Branding Group, Inc. are listed below Please click on an individual job to add any specifications that are still pending.</p>
    <?php foreach($Jobs_result->getRecords() as $Jobs_row){ ?>
      <div id="job" 
      	class="DivHeadToggle"
        width="100%"
        border="0"
        cellspacing="0"
        cellpadding="0"
		onClick="openURL('job.php?j=<?php echo $Jobs_row->getField('job_PK'); ?>')"><?php echo $Jobs_row->getField('JobCmpCUSTOMER::cmpCompany'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobNameWbnd'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobNumberCalc'); ?> <span class="white">|</span> <?php echo $Jobs_row->getField('jobSKUcount'); ?> SKUs</div>
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
