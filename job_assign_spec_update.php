<?php require_once('Connections/ContactLogin.php'); ?>
<?php

$assigned_string = $_POST['assigned_string'];
$job_upc_pk = $_POST['job_upc_pk'];
//$formatted_string = str_replace(" ","\r",$assigned_string);
$upc_pk_arr = explode("/", $assigned_string, -1);
$job_upc_pk_arr = explode("/", $job_upc_pk, -1);
$die_pk = $_REQUEST['s'];

// FOR EACH LOOP UPC
foreach ($job_upc_pk_arr as $upc) 
{ 
	$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
	$UPC_findCriterions = array('upc_PK'=>$upc,);
	foreach($UPC_findCriterions as $key=>$value) {
		$UPC_find->AddFindCriterion($key,$value);
	}
	
	fmsSetPage($UPC_find,'UPC',1); 
	
	$UPC_result = $UPC_find->execute(); 
	
	if(FileMaker::isError($UPC_result)) fmsTrapError($UPC_result,"error.php"); 
	
	fmsSetLastPage($UPC_result,'UPC',1); 
	
	$UPC_row = current($UPC_result->getRecords());
	
	$UPC__calc_portal = fmsRelatedRecord($UPC_row, 'calc');
	$UPC__JobCmpSUPPLIER_portal = fmsRelatedRecord($UPC_row, 'JobCmpSUPPLIER');

	// SET VARS
	$item_upc_recid = $UPC_row->getRecordId();
	$item_upc_pk = $UPC_row->getField('upc_PK');
	$item_upc_fk_die_arr = explode("\n", $UPC_row->getField('upc_FK_spe'));
	$die_pk_arr = array($die_pk);
	$new_upc_fk_die = "";
	
	print_r($item_upc_fk_die_arr);
	echo "<br>";
	if(in_array($item_upc_pk, $upc_pk_arr)) { 										// upc on the list to be assigned
	
		if (in_array($die_pk, $item_upc_fk_die_arr)) { 								// die already assigned
			$new_upc_fk_die = implode("\r", $item_upc_fk_die_arr);
			echo "already assigned<br><br>";
		} else {																	// add die to assigned list
			array_push($item_upc_fk_die_arr, $die_pk);
			$new_upc_fk_die = implode("\r", $item_upc_fk_die_arr);
			echo "new assignment<br><br>";
		}
		
	} else {
		
		if (in_array($die_pk, $item_upc_fk_die_arr)) {								// remove die from assigned list
			
			$die_key = array_search($die_pk, $item_upc_fk_die_arr);
			echo "needs to be removed<br><br>";
			echo "die_key: ";
			print_r($die_key);
			unset($item_upc_fk_die_arr[$die_key]);
			
			$new_upc_fk_die = implode("\r", $item_upc_fk_die_arr);
			echo "<br>new string: " . $new_upc_fk_die;
			//echo $item_upc_fk_die_arr[$die_key];
			
		} else {
			
			echo "leave it alone<br><br>";
			
		}
		
	}
	
	// UPDATE UPC
	$EDIT_UPC_edit = $ContactLogin->newEditCommand('sup-UPC-assign',$item_upc_recid);
	$EDIT_UPC_fields = array('upc_FK_spe'=>$new_upc_fk_die,);
	foreach($EDIT_UPC_fields as $key=>$value) {
		$EDIT_UPC_edit->setField($key,$value);
	}
	
	$EDIT_UPC_result = $EDIT_UPC_edit->execute(); 
	
	if(FileMaker::isError($EDIT_UPC_result)) fmsTrapError($EDIT_UPC_result,"error.php"); 
	
	$EDIT_UPC_row = current($EDIT_UPC_result->getRecords());
}


fmsRedirect($_REQUEST['job']); 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update Job Symbol Assignments</title>
</head>

<body>
<h2>Update Job Spec Assignments</h2>
<p><strong>assigned string: </strong><?php print $assigned_string; ?></p>
<p><strong>upc_pk_arr:</strong> <?php print_r($upc_pk_arr); ?></p>
<p><strong>die_pk:</strong> <?php echo $die_pk; ?></p>
</body>
</html>
