<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Contact_find = $ContactLogin->newFindCommand('sup-conLogin');
$Contact_findCriterions = array('conWebUser'=>'=='.fmsEscape($_SESSION["ContactLogin_tableLogin"]["user"]),'conWebPass'=>'=='.fmsEscape($_SESSION["ContactLogin_tableLogin"]["pass"]),);
foreach($Contact_findCriterions as $key=>$value) {
    $Contact_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contact_find,'Contact',1); 

$Contact_result = $Contact_find->execute(); 

if(FileMaker::isError($Contact_result)) fmsTrapError($Contact_result,"error.php"); 

fmsSetLastPage($Contact_result,'Contact',1); 

$Contact_row = current($Contact_result->getRecords());

$Contact__Sup_portal = fmsRelatedRecord($Contact_row, 'Sup');
$Contact__calc_portal = fmsRelatedRecord($Contact_row, 'calc');
 
 /* PRINTER SITE SNIFFER */
if($Contact_row->getField('Sup::cmp_PK_sup') == "" && $Contact_row->getField('Sup::cmpFlagPrinter') == 1){
	fmsRedirect('../printrak/index.php'); 
	die();
} 



// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php $_SESSION['cmp_ID'] = $Contact_row->getField('con_FK_cmp'); ?>
<?php $_SESSION['conName'] = $Contact_row->getField('conNameFull'); ?>
<?php $_SESSION['supplierName'] = $Contact_row->getField('Sup::cmpCompany'); ?>
<?php 
if($Contact_row->getField('conSubscriberAgreement') <> 1) {
	fmsRedirect('subscriber_agreement.php'); 
  die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>SpecTrak: <?php echo $Contact_row->getField('Sup::cmpCompany'); ?></title>
	<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
	<style type="text/css"> 
	/* place css fixes for all versions of IE in this conditional comment */
	.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
	.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
	/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
	</style>
	<![endif]-->

	<link rel="icon" type="image/gif" href="img/favicon.gif">
	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

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
    <h1><?php echo $Contact_row->getField('Sup::cmpCompany'); ?> | <?php echo $Contact_row->getField('conNameFull'); ?></h1>
  </div>
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="links" class="MenuBarVertical">
	<li><a href="index.php">Home</a></li>
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
	<li><a href="supplier.php">Update Company Contact Info</a></li>
    <li><a href="jobs.php">Jobs</a></li>
  </ul>

<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">
  	<h1>Welcome to Spectrak</h1>
    <p>SpecTrak is a Galileo Global Branding Group, Inc. proprietary, web-based project management tool that allows our supplier-partners to update or provide all of the specifications required to begin a package design job.&nbsp; Our secure servers ensure that all information is confidential.</p>

	<h2>GETTING STARTED</h2>
    <ul>
      <li>To begin the process, click on the <em>Jobs</em> button in the side menu to view all open jobs.</li>
      <li>Choose <em>Update Company Contact Info</em> to update supplier names and addresses.</li>
      <li>To return to the home screen from any page, click on the Home button or SPECTRAK logo.</li>
    </ul>
    <h2>Manual</h2>
    <ul>
      <li><a href="img/SpecTrak_Supplier_Manual_5-13.pdf" target="_blank">SpecTrak User Manual</a></li>
    </ul>
    <h2>Privacy Statement</h2>
    <ul>
      <li><a href="img/SpeckTrak Privacy Agreement.pdf" target="_blank">SpecTrak Privacy Statement</a></li>
    </ul>
	<!--
    <h2>Browser Recommendation</h2>
	<ol>
		<li>Chrome (31+)</li>
		<li>Safari (5.1+)</li>
		<li>Firefox (26+)</li>
		<li>Internet Explorer (11+)</li>
		<li>Opera (19+)</li>
	</ol> -->
  </div>
	  <!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats -->
	  <br class="clearfloat" />
  </h1>
	<div id="footer">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" valign="MIDDLE">© Galileo Global Branding Group, Inc.</td>
        <td valign="MIDDLE" height="25">&nbsp;</td>
        <td valign="MIDDLE" height="25" width="200"></td>
        <td valign="MIDDLE" height="25" width="50"><a href="<?php echo fmsLogoutLink('ContactLogin', 'login.php'); ?>">Logout</a></td>
      </tr>
    </table>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
	<!--
	var MenuBar1 = new Spry.Widget.MenuBar("links", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	var MenuBar2 = new Spry.Widget.MenuBar("printers", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	//-->
</script>
</body>
</html>
