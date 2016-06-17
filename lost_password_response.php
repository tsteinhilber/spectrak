<?php require_once('Connections/LostPwdLogin.php'); ?>
<?php
$Contact_find = $LostPwdLogin->newFindCommand('sup-conLogin');
$Contact_findCriterions = array('conWebUser'=>'=='.fmsEscape($_REQUEST['login_user']),);
foreach($Contact_findCriterions as $key=>$value) {
    $Contact_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contact_find,'Contact',1); 

$Contact_result = $Contact_find->execute(); 

if(FileMaker::isError($Contact_result)) fmsTrapError($Contact_result,"lost_password.php"); 

fmsSetLastPage($Contact_result,'Contact',1); 

$Contact_row = current($Contact_result->getRecords());
 
?>

<?php 

$passFail = "";

// formating the mail posting
$headers4="admin@specktrak.com"; // Change this address within quotes to your address
$headers.="Reply-to: $headers4\n";
$headers .= "From: $headers4\n"; 
$headers .= "Errors-to: $headers4\n"; 
//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;
// for html mail un-comment the above line

$to = $Contact_row->getField('conWebUser');
$subject = "Daymon SpecTrak: Login Details";
$body = $Contact_row->getField('conNameFull') . ",\n\nThis is in response to your request for login details\n\nPassword: " . $Contact_row->getField('conWebPass');
if (mail($to, $subject, $body)) {
	$passFail = "pass";
} else {
	$passFail = "fail";
}

?>

<script language="JavaScript">
var passFail = '<?php echo $passFail; ?>';
if(passFail == 'pass'){
	window.location = 'login.php?passFail=pass';
} else {
	window.location = 'lost_password.php?passFail=fail';
}
</script>

