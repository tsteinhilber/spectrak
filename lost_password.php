<?php
require_once('FMStudio_v2/FMStudio_Tools.php');
$loginError = "";
$passFail = "";
if(isset($_GET["errorMsg"])){
	$loginError = $_GET["errorMsg"];
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
<title>SpecTrak: Lost Password</title>
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
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<!-- JAVASCRIPT -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>

<script language="JavaScript">
var loginError = '<?php echo $loginError; ?>';
var passFail = '<?php echo $passFail; ?>';

$(document).ready(function(){
	$("#login_error").hide();
	$("input:text:visible:first").focus();
	if(loginError){
		$("#lostPwd_instructions").hide();
		$("#login_error").addClass('errorVar');
		$("#login_error").fadeIn('slow');
	} else if(passFail == 'fail') {
		$("#lostPwd_instructions").hide();
		$("#login_error").text('Request failed');
		$("#login_error").addClass('errorVar');
		$("#login_error").fadeIn('slow');
	}
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
    <h1>Lost Password </h1>
</div><!-- end #header -->

<form id="login_form" name="login_form" method="post" action="lost_password_response.php">
  <div id="login_content">
  <div id="login_error"><?php echo $loginError; ?></div>
  <div id="lostPwd_instructions"><span class="callout">Instructions:</span> Enter your user name. You will receive an email with your information.</div>
  <table id="login" class="table_lostPwd" border="0" bgcolor="#FFF" cellpadding="5" cellspacing="5">
    <tr>
      <td class="table_login_side" nowrap="nowrap" scope="row" width="100">User Name</td>
      <td><span id="sprytextfield1">
      <input type="text" name="login_user" id="login_user" accesskey="u" tabindex="1">
      <span class="textfieldRequiredMsg"><br />You're email is required.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Must be an email address.</span></span></td>
    </tr>
  </table>
  <p align="center"><input id="login_btn" type="submit" name="Submit" value="Send" /></p>
  </div>
</form>
</div>

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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {maxChars:35});
//-->
</script>
</body>
<?php unset($_SESSION['passFail']); ?>
</html>
