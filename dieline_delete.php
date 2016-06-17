<?php require_once('Connections/ContactLogin.php'); ?>
<?php

error_reporting(0);

$DIELINE_find = $ContactLogin->newFindCommand('sup-Dielines');
$DIELINE_findCriterions = array('_PrimeDieIDX'=>'=='.fmsEscape($_REQUEST['item']),);
foreach($DIELINE_findCriterions as $key=>$value) {
    $DIELINE_find->AddFindCriterion($key,$value);
}

fmsSetPage($DIELINE_find,'DIELINE',1); 

$DIELINE_result = $DIELINE_find->execute(); 

if(FileMaker::isError($DIELINE_result)) fmsTrapError($DIELINE_result,"error.php"); 

fmsSetLastPage($DIELINE_result,'DIELINE',1); 

$DIELINE_row = current($DIELINE_result->getRecords());

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
$itemFileName = $DIELINE_row->getField('DieFileName');
$itemRecID = $DIELINE_row->getRecordId();
$itemCompany = $DIELINE_row->getField('Sup::cmpCompanyFolderName');
$itemPath = "D:/Supplier Files/" . $itemCompany . "/Dielines/";


// FIND UPC ------------------
$UPC_find = $ContactLogin->newFindCommand('sup-UPC-assign');
$UPC_findCriterions = array('upc_FK_die'=>$_REQUEST['item'],);
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
	echo getcwd() . "<br>";
	
	/* list directory contrents
	if ($handle = opendir($itemPath)) {
		echo "<b>Directory handle:</b> $handle<br>";
		echo "<h2>DIRECTORY CONTENTS:</h2>";
		while (false !== ($entry = readdir($handle))) {
			echo "$entry<br>";
		}
		closedir($handle);
	} */
	
	if(is_file($itemFileName)){
		//echo "file found<br>";
	} else {
		//echo "file does not exist<br>";
	}
	//echo "<b>" . $itemFileName . "</b> is not assigned.<br><b>itemRecID:</b> " . $itemRecID . "<br>";
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
		$DELETE_DIELINE_delete = $ContactLogin->newDeleteCommand('sup-Dielines',$itemRecID);
		$DELETE_DIELINE_result = $DELETE_DIELINE_delete->execute();
		if(FileMaker::isError($DELETE_DIELINE_result)) fmsTrapError($DELETE_DIELINE_result,"error.php");
		// echo "record deleted...  ";
		header( "Location: $deletedAssignedURL" );
	}
}

?>