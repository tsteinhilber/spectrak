<?php

session_destroy();
require_once('FMStudio_v2/FMStudio_Tools.php');
//error_reporting(0);

// CHECK CONNECTION TO FMP SERVER
require_once('Connections/connection_test.php');
$found_records_find = $connection_test->newFindCommand('sup-conLogin');
$found_records_findCriterions = array('con_PK'=>'con106778',);
foreach($found_records_findCriterions as $key=>$value) {
    $found_records_find->AddFindCriterion($key,$value);
}

fmsSetPage($found_records_find,'found_records',1); 

$found_records_result = $found_records_find->execute(); 

if(FileMaker::isError($found_records_result)) fmsTrapError($found_records_result,"site_down.php"); 

fmsSetLastPage($found_records_result,'found_records',1); 

$found_records_row = current($found_records_result->getRecords());

$_SESSION = array();

$_GET["errorMsg"] = fmsPerformLogin();
$loginError = "";
$passFail = "";
if(isset($_GET["errorMsg"])){
	$loginError = $_GET["errorMsg"];
  $_SESSION['ContactLogin_tableLogin']['first'] = false;
}
if(isset($_GET["passFail"])){
	$passFail = $_REQUEST["passFail"];
}



// FMStudio v2 - do not remove comment, needed for Dreamweaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak: Please Login</title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->
<link rel="icon" type="image/gif" href="img/favicon.gif">
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPT -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>

<script language="JavaScript">
var loginError = '<?php echo $loginError; ?>';
var passFail = '<?php echo $passFail; ?>';

$(document).ready(function(){
	$("#login_error").hide();
	$("input:text:visible:first").focus();
	if(loginError){
		$("#login_error").addClass('errorVar');
		$("#login_error").fadeIn('slow');
	} else if(passFail == 'pass') {
		$("#login_error").text('Request sent');
		$("#login_error").addClass('pass');
		$("#login_error").fadeIn('slow');
	}
	$("#lost_pwd").click(function(){
		//alert("clicked lost_pwd btn");
		window.location = "lost_password.php";
	});
});
</script>
</head>

<body class="twoColHybLtHdr">

<div id="container">

<!-- HEADER -->
<div id="header">
    <div id="logo">
        <ul id="clientscape_header" class="headerLogo">
            <li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
          </ul>
        </div>
</div><!-- end #header -->

<form id="login_form" name="login_form" method="post" action="">
  <div id="login_content">
  <div id="login_error"><?php echo $_GET["errorMsg"]; ?></div>
  <table id="login" class="table_login" border="0" bgcolor="#FFF" cellpadding="5" cellspacing="5">
    <tr>
      <td nowrap="nowrap" class="table_login_side" scope="row">User Name</td>
      <td><span id="sprytextfield1">
      <input type="text" name="login_user" id="login_user" accesskey="u" tabindex="1">
      <span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Must be an email address.</span></span></td>
    </tr>
    <tr>
      <td width="100" class="table_login_side" scope="row">Password</td>
      <td><span id="sprypassword1">
      <input type="password" name="login_pass" id="login_pass" accesskey="p" tabindex="2">
      <span class="passwordMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
    </tr>
  </table>
  <p><input id="login_btn" type="submit" name="Submit" value="Login" /><input name="lost_pwd" type="button" value="Lost Password" id="lost_pwd" class="btn_generic" /></p>
  </div>
</form>
</div>

<!-- <h1>Referrer: <?php echo $_SERVER['HTTP_REFERER']; ?></h1> -->
<!--
<h1><? echo $_SESSION['login_from']; ?></h1>
<hr />
<h1><? echo $_SESSION['ContactLogin_tableLogin']['user'] ?> | <? echo $_SESSION['ContactLogin_tableLogin']['pass'] ?></h1>
<hr />
<h1>Escaped: <? echo fmsEscape($_SESSION['ContactLogin_tableLogin']['user']) ?> | <? echo fmsEscape($_SESSION['ContactLogin_tableLogin']['pass']) ?></h1>
-->
<p>Session: <? print_r($_SESSION); ?></p>
<p>Error Message: <? echo $_GET["errorMsg"] ?></p>

<!-- FOOTER -->
<div id="footerLogin">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" valign="MIDDLE" align="LEFT">&copy;  Galileo Global Branding Group, Inc.</td>
      </tr>
    </table>
</div>

<script type="text/javascript">
<!--

var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["change"], maxChars:100});
//-->
</script>
</body>
<?php unset($_SESSION['passFail']); ?>
</html>
