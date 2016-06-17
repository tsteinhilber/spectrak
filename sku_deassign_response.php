<?php require_once('Connections/ContactLogin.php'); ?>
<?php

$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
$UPC_findCriterions = array('upc_PK'=>'='.fmsEscape($_REQUEST['u']),);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

fmsSetPage($UPC_find,'UPC',1); 

$UPC_result = $UPC_find->execute(); 

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 

fmsSetLastPage($UPC_result,'UPC',1); 

$UPC_row = current($UPC_result->getRecords());

$UPC__JOB_portal = fmsRelatedRecord($UPC_row, 'JOB');

$recid = $_REQUEST["-recid"];
$upcid = $_REQUEST["u"];
$deassign_field = $_REQUEST["deassign_field"];
$deassign_item = $_REQUEST["deassign_item"];
$deassign_name = $_REQUEST["deassign_name"];

switch ($deassign_field) {
	case "dieline":
		$field = 'upc_FK_die';
		$keys = $UPC_row->getField('upc_FK_die');
		break;
	case "document":
		$field = 'upc_FK_doc';
		$keys = $UPC_row->getField('upc_FK_doc');
		break;
	case "image":
		$field = 'upc_FK_img';
		$keys = $UPC_row->getField('upc_FK_img');
		break;
	case "spec":
		$field = 'upc_FK_spe';
		$keys = $UPC_row->getField('upc_FK_spe');
		break;
	case "symbol":
		$field = 'upc_FK_sym';
		$keys = $UPC_row->getField('upc_FK_sym');
		break;
}

$keys_arr = explode("\n", $keys);
$item_key = array_search($deassign_item, $keys_arr);
unset($keys_arr[$item_key]);
$new_keys = implode("\r", $keys_arr);

$redirect = $_SERVER['HTTP_REFERER'];
$infoPassFail = "pass";
$infoReturned = $deassign_name . " has been deassigned";

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<?php

$UPDATE_UPC_edit = $ContactLogin->newEditCommand('sup-UPC-assign',$_REQUEST['-recid']);
$UPDATE_UPC_fields = array($field=>$new_keys,);
foreach($UPDATE_UPC_fields as $key=>$value) {
    $UPDATE_UPC_edit->setField($key,$value);
}

$UPDATE_UPC_result = $UPDATE_UPC_edit->execute(); 

if(FileMaker::isError($UPDATE_UPC_result)) fmsTrapError($UPDATE_UPC_result,"error.php"); 

$UPDATE_UPC_row = current($UPDATE_UPC_result->getRecords()); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak</title>

<script type="text/javascript">
function redirect(){
	document.returnInfo.submit();
}
</script>

</head>

<body onload="redirect()" style='display:none;'>
<p>recid: <?php echo $recid ?></p>
<p>u: <?php echo $upcid; ?></p>
<p>deassign_field: <?php echo $deassign_field; ?></p>
<p>deassign_item: <?php echo $deassign_item; ?></p>
<p>keys: <?php echo $keys; ?></p>
<p>keys_arr: <?php print_r($keys); ?></p>
<p>new keys: <?php echo $new_keys; ?></p>
<p><?php echo $UPC_row->getField('upcPDKStatus'); ?></p>

<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $redirect; ?>">
  <input id="skuInfoReturned" name="skuInfoReturned" type="input" value="<?php echo $infoReturned; ?>">
  <input id="skuInfoPassFail" name="skuInfoPassFail" type="input" value="<?php echo $infoPassFail; ?>">
</form>

</body>
</html>
