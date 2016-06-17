<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$backToDetail = $_REQUEST['backToDetail'];
$redirect = $_SERVER['HTTP_REFERER'];
$redirectLastSix = substr($redirect, -6);
if($redirectLastSix == "&del=a" || $redirectLastSix == "&del=d"){
	$redirect = substr($redirect, 0, -6);
}

$assigned_upc = $_POST['assigned_upc'];
$job_upc_pk = $_POST['job_upc_pk'];
$upc_pk_arr = explode("/", $assigned_upc, -1);
$job_upc_pk_arr = explode("/", $job_upc_pk, -1);
$doc_pk = $_REQUEST['assigned_item'];

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
	$item_upc_fk_doc_arr = explode("\n", $UPC_row->getField('upc_FK_doc'));
	$doc_pk_arr = array($doc_pk);
	$new_upc_fk_doc = "";
	$assignment = FALSE;
	
	$PDKstatusOrig = $UPC_row->getField('upcPDKStatus');
	$PDKstatus = $UPC_row->getField('upcPDKStatus');
	
	switch ($PDKstatus) {
		case "":
			$PDKstatus = "Active";
			break;
		case "New":
			$PDKstatus = "Active";
			break;
		case "Active":
			$PDKstatus = "Active";
			break;
		case "Submitted":
			$PDKstatus = "Submitted";
			break;
		case "Complete":
			$PDKstatus = "Complete";
			break;
		case "Submitted Incomplete":
			$PDKstatus = "Submitted Incomplete";
			break;
	}
	
	//echo "<b>item_upc_fk_doc_arr:</b> ";
	//print_r($item_upc_fk_doc_arr);
	//echo "<br>";
	if(in_array($item_upc_pk, $upc_pk_arr)) { 										// upc on the list to be assigned
	
		if (in_array($doc_pk, $item_upc_fk_doc_arr)) { 								// doc already assigned
			//$new_upc_fk_doc = implode("\r", $item_upc_fk_doc_arr);
			//echo "already assigned<br>";
		} else {																	// add doc to assigned list
			array_push($item_upc_fk_doc_arr, $doc_pk);
			$new_upc_fk_doc = implode("\r", $item_upc_fk_doc_arr);
			$assignment = TRUE;
			//echo "new assignment<br>";
		}
		
	} else {
		
		if (in_array($doc_pk, $item_upc_fk_doc_arr)) {								// remove doc from assigned list
			
			$doc_key = array_search($doc_pk, $item_upc_fk_doc_arr);
			$assignment = TRUE;
			//echo "needs to be removed<br>";
			//echo "doc_key: ";
			//print_r($doc_key);
			unset($item_upc_fk_doc_arr[$doc_key]);
			$new_upc_fk_doc = implode("\r", $item_upc_fk_doc_arr);
			//echo "<br>new string: " . $new_upc_fk_doc;
			//echo $item_upc_fk_doc_arr[$doc_key];
			
		} else {
			
			//echo "leave it alone<br>";
			
		}
		
	}
	//echo "<b>new_upc_fk_doc:</b> " . $new_upc_fk_doc . "<br><br>";
	
	// UPDATE UPC
	if($assignment){
		
		$EDIT_UPC_edit = $ContactLogin->newEditCommand('sup-UPC-assign',$item_upc_recid);
		$EDIT_UPC_fields = array('upc_FK_doc'=>$new_upc_fk_doc,'upcPDKStatus'=>$PDKstatus,);
		foreach($EDIT_UPC_fields as $key=>$value) {
			$EDIT_UPC_edit->setField($key,$value);
		}
		
		$EDIT_UPC_result = $EDIT_UPC_edit->execute(); 
		
		if(FileMaker::isError($EDIT_UPC_result)) fmsTrapError($EDIT_UPC_result,"error.php"); 
		
		$EDIT_UPC_row = current($EDIT_UPC_result->getRecords());
		
	}
}


//fmsRedirect($_REQUEST['job']); 

$errorVar = 'Document assigned';
$passFail = 'pass';

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update Document Assignments</title>

<script type="text/javascript">
function redirect(){
	  document.returnInfo.submit();
}
</script>

</head>

<body onload="redirect()">
<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $redirect; ?>">
  <input id="backToDetail" name="backToDetail" type="hidden" value="<?php echo $backToDetail; ?>">
  <input id="infoReturned" name="infoReturned" type="hidden" value="<?php echo $errorVar ?>">
  <input id="infoPassFail" name="infoPassFail" type="hidden" value="<?php echo $passFail ?>">
  <input id="assigned_item" name="assigned_item" type="hidden" value="<?php echo $doc_pk ?>">
</form>
</body>
</html>
