<?php require_once('Connections/ContactLogin.php'); ?>

<?php

error_reporting(0);

// VARS ----------------------------

$backToDetail = $_REQUEST['backToDetail'];
$redirect = $_SERVER['HTTP_REFERER'];$redirectLastSix = substr($redirect, -6);
if($redirectLastSix == "&del=a" || $redirectLastSix == "&del=d"){
	$redirect = substr($redirect, 0, -6);
}

//$cmpCompany =  $_REQUEST['cmpCompany'];
if (isset($_REQUEST['cmpCompany'])){
	$cmpCompany =  $_REQUEST['cmpCompany'];
} else {
	$cmpCompany =  "_LOST";
}

$assigned_string = $_POST['assigned_upc'];
$success_text = "New document created";
$upc_pk_arr = explode("/", $assigned_string, -1);

$doc_pk = $_REQUEST['d'];
$path_root = "D:/Supplier Files/" . $cmpCompany . "/";
$uploaddir = "D:/Supplier Files/" . $cmpCompany . "/Documents/";														// Upload directory: needs write permissions
$log = "uploadlog.txt"; 																								// Upload LOG file
$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".exe", ".js",".html", ".htm", ".inc"); 				// what file types do you want to disallow?
$allowed_filetypes = array('.eps','.pdf','.ai','.zip', '.sitx', '.doc', '.docx', '.txt', '.xls', '.xlsx', '.rtf'); 		// allowed filetypes

// DIRECTORY CREATION CODE ----------------------------------------------

mkdir($uploaddir, 0777, true);
if (is_dir($uploaddir)) {
    $directory_creation_comment = 'directory exists!';
	chmod($path_root, 0777);
	chmod($uploaddir, 0777);
} else {
	$directory_creation_comment = 'failed to create folders';
}

// CHECK FOR EXISTING FILENAME IN FILEMAKER  ----------------------------------------------
$Document_find = $ContactLogin->newFindCommand('sup-Documents');
$Document_findCriterions = array('doc_FK_cmp'=>'=='.fmsEscape($_REQUEST['doc_FK_cmp']),'docFileName'=>$_FILES["file"]["name"],);
foreach($Document_findCriterions as $key=>$value) {
    $Document_find->AddFindCriterion($key,$value);
}

fmsSetPage($Document_find,'Document',1); 

$Document_result = $Document_find->execute();

if(!FileMaker::isError($Document_result)){
	$errorVar =  "This Document already exists, you may assign existing Documents to SKUs";
} else {

	// UPLOAD CODE -----------------------------------------------------------

	if (!is_dir($uploaddir)) {
		$errorVar =  "Upload directory does not exists.";
	}
	if (!is_writable($uploaddir)) {
		$errorVar =  "Upload directory is not writable.";
	}
 
	if ($_POST['cmdupload'])
	{
 
	$ip = trim($_SERVER['REMOTE_ADDR']);
 
		if (isset($_FILES['file']))
		{
			if ($_FILES['file']['error'] != 0)
			{
				switch ($_FILES['file']['error'])
				{
					case 1:
						$errorVar =  'The file is to big.'; // php installation max file size error
						exit;
						break;
					case 2:
						$errorVar =  'The file is to big.'; // form max file size error
						exit;
						break;
					case 3:
						$errorVar =  'Only part of the file was uploaded';
						exit;
						break;
					case 4:
						$errorVar =  'No file was uploaded</p>';
						exit;
						break;
					case 6:
						$errorVar =  "Missing a temporary folder.";
						exit;
						break;
					case 7:
						$errorVar =  "Failed to write file to disk";
						exit;
						break;
					case 8:
						$errorVar =  "File upload stopped by extension";
						exit;
						break;
 
				}
			} else {
				foreach ($blacklist as $item)
				{
					if (preg_match("/$item\$/i", $_FILES['file']['name']))
					{
						$errorVar =  "Invalid filetype !";
						 	$date = date("m/d/Y");
							$time = date("h:i:s A");
							$fp = fopen($log,"ab");
							fwrite($fp,"$ip | ".$_FILES['file']['name']." | $date | $time | INVALID TYPE"."\r\n");
							fclose($fp);
						unset($_FILES['file']['tmp_name']);
						exit;
					}
				}
				// Get the extension from the filename.
				$ext = substr($_FILES['file']['name'], strrpos($_FILES['file']['name'],'.'), strlen($_FILES['file']['name'])-1);
	   			// Check if the filetype is allowed, if not DIE and inform the user.
	   			if(!in_array($ext,$allowed_filetypes)){
							$date = date("m/d/Y");
							$time = date("h:i:s A");
							$fp = fopen($log,"ab");
							fwrite($fp,"$ip | ".$_FILES['file']['name']." | $date | $time | INVALID TYPE"."\r\n");
							fclose($fp);
		  					$errorVar =  "The file you attempted to upload is not allowed.";
				}
				if (!file_exists($uploaddir . $_FILES["file"]["name"]))
				{
					// Proceed with file upload
					if (is_uploaded_file($_FILES['file']['tmp_name']))
					{
						// File was uploaded to the temp dir, continue upload process
						if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $_FILES['file']['name']))
						{
							// uploaded file was moved and renamed succesfuly. Display a message.
							$errorVar =  $success_text;
							$passFail = "pass";
							$active_flag = 1;
						
							// CREATE NEW DOCUMENT RECORD ------------------------------------------------------------
							$new_document_add = $ContactLogin->newAddCommand('sup-Documents');
							$new_document_fields = array('DocActiveFlag'=>$active_flag,'docDescription'=>$_REQUEST['docDescription'],'doc_FK_cmp'=>$_REQUEST['doc_FK_cmp'],'docFileName'=>$_FILES["file"]["name"],);
							foreach($new_document_fields as $key=>$value) {
								$new_document_add->setField($key,$value);
							}
						
							$new_document_result = $new_document_add->execute(); 
						
							if(FileMaker::isError($new_document_result)) fmsTrapError($new_document_result,"error.php"); 
						
							$new_document_row = current($new_document_result->getRecords());
						
							// NEW RECORD VARS ------------------------------------------------------
							//$new_item_pk = "";
							$new_item_pk = $new_document_row->getField('_PrimeDocIDX');
							$new_item_name = $new_document_row->getField('docName');
							$new_item_description = $new_document_row->getField('docDescription');
							$new_item_file_name = $new_document_row->getField('docFileName');
						
						
							// ASSIGN TO UPC (for each sent upc) ----------------------------------------------------------------------------------
							foreach ($upc_pk_arr as $upc) 
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
							
								// $item_upc_fk_doc = "";
								// settype($item_upc_fk_doc, "string");
								$item_upc_fk_doc = $UPC_row->getField('upc_FK_doc');
								$item_upc_fk_doc_arr = explode("\n", $UPC_row->getField('upc_FK_doc'));
								array_push($item_upc_fk_doc_arr, $new_item_pk);
								$new_upc_fk_doc = implode("\r", $item_upc_fk_doc_arr);
								// $new_upc_fk_doc = $new_item_pk . "\r" . $item_upc_fk_doc;
								
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
							
								// UPDATE UPC
								$EDIT_UPC_edit = $ContactLogin->newEditCommand('sup-UPC-assign',$item_upc_recid);
								$EDIT_UPC_fields = array('upc_FK_doc'=>$new_upc_fk_doc,'upcPDKStatus'=>$PDKstatus,);
								foreach($EDIT_UPC_fields as $key=>$value) {
									$EDIT_UPC_edit->setField($key,$value);
								}
							
								$EDIT_UPC_result = $EDIT_UPC_edit->execute(); 
							
								if(FileMaker::isError($EDIT_UPC_result)) fmsTrapError($EDIT_UPC_result,"error.php"); 
							
								$EDIT_UPC_row = current($EDIT_UPC_result->getRecords());
							}
						
							// REDIRECT ---------------------------------------------------------------
							//fmsRedirect('sku.php?u=' . $_REQUEST['u']);
						
							// Now log the uploaders IP adress date and time
							$date = date("m/d/Y");
							$time = date("h:i:s A");
							$fp = fopen($log,"ab");
							fwrite($fp,"$ip | ".$_FILES['file']['name']." | $date | $time | OK"."\r\n");
							fclose($fp);
						
						} else {
							$errorVar =  "Error while uploading the file, Please contact the webmaster.";
							unset($_FILES['file']['tmp_name']);
						}
					} else {
						//File was NOT uploaded to the temp dir
						switch ($_FILES['file']['error'])
						{
							case 1:
								$errorVar =  'The file is to big.'; // php installation max file size error
								break;
							case 2:
								$errorVar =  'The file is to big.'; // form max file size error
								break;
							case 3:
								$errorVar =  'Only part of the file was uploaded';
								break;
							case 4:
								$errorVar =  'No file was uploaded</p>';
								break;
							case 6:
								$errorVar =  "Missing a temporary folder.";
								break;
							case 7:
								$errorVar =  "Failed to write file to disk";
								break;
							case 8:
								$errorVar =  "File upload stopped by extension";
								break;
 
						}
 
					}
				} else {
					$errorVar =  "Filename already exists, Please rename the file and retry.";
					unset($_FILES['file']['tmp_name']);
				}
			}
		} else {
			// user did not select a file to upload
			$errorVar =  "Please select a file to upload.";
		}
	} else {
		// upload button was not pressed
		header("Location: dieline_new.php");
	}
}

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/php; charset=UTF-8">
<title>New Document Response</title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function redirect(){
	  document.returnInfo.submit();
}
</script>
</head>

<body onload="redirect()">
<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $redirect; ?>">
  <input id="redirect" name="redirect" type="hidden" value="<?php echo $redirect; ?>">
  <input id="backToDetail" name="backToDetail" type="hidden" value="<?php echo $backToDetail; ?>">
  <input id="infoReturned" name="infoReturned" type="hidden" value="<?php echo $errorVar ?>">
  <input id="infoPassFail" name="infoPassFail" type="hidden" value="<?php echo $passFail ?>">
  <input id="newItemName" name="newItemName" type="hidden" value="<?php echo $new_item_name; ?>">
  <input id="newItemDescription" name="newItemDescription" type="hidden" value="<?php echo $new_item_description; ?>">
  <input id="newItemFileName" name="newItemFileName" type="hidden" value="<?php echo $new_item_file_name; ?>">
  <input id="assigned_item" name="assigned_item" type="hidden" value="<?php echo $new_item_pk; ?>">
</form>

</body>
</html>
