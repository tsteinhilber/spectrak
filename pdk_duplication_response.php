<?php require_once('Connections/ContactLogin.php'); ?>

<?php
	// VARS
	$dup_upc = $_POST['dup_upc'];
	$assigned_upc = $_POST['assigned_upc'];
	$upc_array = explode("/", $assigned_upc, -1);
	$first_upc = $upc_array[0];
	$pdk_type = $_REQUEST['pdk_type'];
	$layoutName = "sup-UPC";
	$scriptName = "SpecTrak - Duplicate PDKs";
	$scriptParameter = $_POST['dup_upc']."\n".$_POST['assigned_upc'];
	
	echo $pdk_type . "<br>";
	echo $first_upc . "<br>";
	echo $dup_upc . "<br>";
	echo $layoutName . "<br>";
	echo $scriptName . "<br>";
	echo $scriptParameter . "<br>";
	
	//CREATE PERFORM SCRIPT COMMAND
	$command = $ContactLogin->newPerformScriptCommand($layoutName, $scriptName, $scriptParameter);
	
	//EXECUTE THE COMMAND
	$result = $command->execute();
	
	// die();
	$redirect_url = "sku_" . $pdk_type . ".php?u=" . $first_upc;
	fmsRedirect($redirect_url);
?>

<?php /*
$UPC_find = $ContactLogin->newFindCommand('sup-UPC');
$UPC_findCriterions = array('upc_PK'=>$_REQUEST['dup_upc'],);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

fmsSetPage($UPC_find,'UPC',1); 

$script_param = $_POST['dup_upc']."\n".$_POST['assigned_upc'];

$UPC_find->SetScript('SpecTrak - Duplicate PDKs',$script_param); 

$UPC_result = $UPC_find->execute(); 

if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 

fmsSetLastPage($UPC_result,'UPC',1); 

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
$UPC__JobUpcDoc_portal = fmsRelatedRecord($UPC_row, 'JobUpcDoc');
*/
 // FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php

// VARS 
/*
$assigned_upc = $_POST['assigned_upc'];
$upc_array = explode("/", $assigned_upc, -1);
$first_upc = $upc_array[0];
$pdk_type = $UPC_row->getField('JobUpcSku::skuPDKType');

switch ($pdk_type) {
    case "Food":
        $pdk_type_ab = "f";
        break;
    case "Non-Food":
        $pdk_type_ab = "nf";
        break;
    case "HBC":
        $pdk_type_ab = "hbc";
        break;
}

$redirect_url = "sku_" . $pdk_type_ab . ".php?u=" . $first_upc;

fmsRedirect($redirect_url); 
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Loading...</title>
</head>

<body> 
<p>skuName: <?php echo $UPC_row->getField('JobUpcSku::skuName'); ?></p>
<p>script_param: <?php echo $script_param; ?></p>
</body>
</html>
