<?php require_once('Connections/ContactLogin.php'); ?>
<?php

error_reporting(0);

$IMAGERY_find = $ContactLogin->newFindCommand('sup-Imagery');
$IMAGERY_findCriterions = array('_PrimeImgIDX'=>'=='.fmsEscape($_REQUEST['item']),);
foreach($IMAGERY_findCriterions as $key=>$value) {
    $IMAGERY_find->AddFindCriterion($key,$value);
}

fmsSetPage($IMAGERY_find,'IMAGERY',1); 

$IMAGERY_result = $IMAGERY_find->execute(); 

if(FileMaker::isError($IMAGERY_result)) fmsTrapError($IMAGERY_result,"error.php"); 

fmsSetLastPage($IMAGERY_result,'IMAGERY',1); 

$IMAGERY_row = current($IMAGERY_result->getRecords());

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php

// VARS ------------------
$fromURL = $_SERVER['HTTP_REFERER'];
$fromUrlLastSix = substr($fromURL, -6);
if($fromUrlLastSix == "&del=a" || $fromUrlLastSix == "&del=d"){
	$fromURL = substr($fromURL, 0, -6);
}
// exit($fromURL);

$alreadyAssignedURL = $fromURL . "&del=a";
$deletedAssignedURL = $fromURL . "&del=d";
$itemFileName = $IMAGERY_row->getField('ImgFileName');
$itemRecID = $IMAGERY_row->getRecordId();
$itemCompany = $IMAGERY_row->getField('Sup::cmpCompanyFolderName');
$itemPath = "D:/Supplier Files/" . $itemCompany . "/Imagery/";


// FIND UPC ------------------
$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
$UPC_findCriterions = array('upc_FK_img'=>$_REQUEST['item'],);
foreach($UPC_findCriterions as $key=>$value) {
    $UPC_find->AddFindCriterion($key,$value);
}

fmsSetPage($UPC_find,'UPC',10); 

$UPC_result = $UPC_find->execute(); 

if(FileMaker::isError($UPC_result)) {
	removeItem();
} else {
	header( "Location: $alreadyAssignedURL" );
}

fmsSetLastPage($UPC_result,'UPC',10); 

$UPC_row = current($UPC_result->getRecords());

$UPC__JOB_portal = fmsRelatedRecord($UPC_row, 'JOB');


// REMOVE ITEM FUNCTION --------------
function removeItem(){
	global $ContactLogin, $deletedAssignedURL, $itemFileName, $itemPath, $itemRecID;
	chdir($itemPath);
	/*echo getcwd() . "<br>";
	if(is_file($itemFileName)){
		echo "file found<br>";
	} else {
		echo "file does not exist<br>";
	}
	echo "<b>" . $itemFileName . "</b> is not assigned.<br><b>itemRecID:</b> " . $itemRecID . "<br>"; */
	while(is_file($itemFileName) == TRUE)
	{
		echo "deleting...";
		unlink($itemFileName);
	}
	// CHECK IF FILE HAS BEEN DELETED
	if(is_file($itemFileName)){
		// echo "still a file";
	} else {
		// echo $itemRecID . "  | file deleted...  ";
		$DELETE_IMAGERY_delete = $ContactLogin->newDeleteCommand('sup-Imagery',$itemRecID);
		$DELETE_IMAGERY_result = $DELETE_IMAGERY_delete->execute();
		if(FileMaker::isError($DELETE_IMAGERY_result)) fmsTrapError($DELETE_IMAGERY_result,"error.php");
		// echo "record deleted...  ";
		header( "Location: $deletedAssignedURL" );
	}
}

?>
