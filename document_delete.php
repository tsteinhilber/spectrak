<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$DOCUMENT_find = $ContactLogin->newFindCommand('sup-Documents');
$DOCUMENT_findCriterions = array('_PrimeDocIDX'=>'=='.fmsEscape($_REQUEST['item']),);
foreach($DOCUMENT_findCriterions as $key=>$value) {
    $DOCUMENT_find->AddFindCriterion($key,$value);
}

fmsSetPage($DOCUMENT_find,'DOCUMENT',1); 

$DOCUMENT_result = $DOCUMENT_find->execute(); 

if(FileMaker::isError($DOCUMENT_result)) fmsTrapError($DOCUMENT_result,"error.php"); 

fmsSetLastPage($DOCUMENT_result,'DOCUMENT',1); 

$DOCUMENT_row = current($DOCUMENT_result->getRecords());

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php

error_reporting(0);

// VARS ------------------
$fromURL = $_SERVER['HTTP_REFERER'];
$fromUrlLastSix = substr($fromURL, -6);
if($fromUrlLastSix == "&del=a" || $fromUrlLastSix == "&del=d"){
	$fromURL = substr($fromURL, 0, -6);
}
// exit($fromURL);

$alreadyAssignedURL = $fromURL . "&del=a";
$deletedAssignedURL = $fromURL . "&del=d";
$itemFileName = $DOCUMENT_row->getField('docFileName');
$itemRecID = $DOCUMENT_row->getRecordId();
$itemCompany = $DOCUMENT_row->getField('Sup::cmpCompanyFolderName');
$itemPath = "D:/Supplier Files/" . $itemCompany . "/Documents/";


// FIND UPC ------------------
$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
$UPC_findCriterions = array('upc_FK_doc'=>$_REQUEST['item'],);
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
	//echo "<b>itemFileName:</b> " . $itemFileName . "<br>";
	//echo "<b>Directory:</b> " . getcwd() . "<br>";
	
	/* list directory contrents
	if ($handle = opendir($itemPath)) {
		echo "<b>Directory handle:</b> $handle<br>";
		echo "<h2>DIRECTORY CONTENTS:</h2>";
		while (false !== ($entry = readdir($handle))) {
			echo "$entry<br>";
		}
		closedir($handle);
	}
	
	if(is_file($itemFileName)){
		echo "file found<br>";
	} else {
		echo "file does not exist<br>";
	}
	echo "<b>" . $itemFileName . "</b> is not assigned.<br><b>itemRecID:</b> " . $itemRecID . "<br>"; */
	while(is_file($itemFileName) == TRUE)
	{
		//echo "deleting...<br>";
		unlink($itemFileName);
	}
	// CHECK IF FILE HAS BEEN DELETED
	if(is_file($itemFileName)){
		//echo "still a file<br>";
	} else {
		//echo $itemRecID . "  | file deleted...<br>";
		$DELETE_DOCUMENT_delete = $ContactLogin->newDeleteCommand('sup-Documents',$itemRecID);
		$DELETE_DOCUMENT_result = $DELETE_DOCUMENT_delete->execute();
		if(FileMaker::isError($DELETE_DOCUMENT_result)) fmsTrapError($DELETE_DOCUMENT_result,"error.php");
		//echo "record deleted...  ";
		header( "Location: $deletedAssignedURL" );
	}
}

?>