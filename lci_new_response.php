<?php require_once('Connections/ContactLogin.php'); ?>
<?php

// VARS ----------------------------

$infoPassFail = "fail";
$cmpCompany =  $_REQUEST['cmpCompany'];
$path_root = "../Supplier Files/" . $cmpCompany . "/";
$uploaddir = "../Supplier Files/" . $cmpCompany . "/Label Code Images/";								// Upload directory: needs write permissions
$log = "uploadlog.txt"; 																				// Upload LOG file
$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".exe", ".js",".html", ".htm", ".inc"); // what file types do you want to disallow?
$allowed_filetypes = array('.eps','.pdf','.ai','.zip', '.sitx'); 										// allowed filetypes

// DIRECTORY CREATION CODE ----------------------------------------------

mkdir($uploaddir, 0777, true);
if (is_dir($uploaddir)) {
    $directory_creation_comment = 'directory exists!';
	chmod($path_root, 0777);
	chmod($uploaddir, 0777);
} else {
	$directory_creation_comment = 'failed to create folders';
}

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
						$errorVar =  "New Label Code Image upload successful!";
						$infoPassFail = "pass";
						
						// CREATE NEW LABEL CODE IMAGE RECORD ------------------------------------------------------------
						$new_labelCodeImage_add = $ContactLogin->newAddCommand('sup-LabelCodeImage');
						$new_labelCodeImage_fields = array('lciDescription'=>$_REQUEST['lciDescription'],'lciName'=>$_REQUEST['lciName'],'lciType'=>$_REQUEST['lciType'],'lciFileName'=>$_FILES["file"]["name"],'lci_FK_cmp'=>$_REQUEST['lci_FK_cmp'],);
						foreach($new_labelCodeImage_fields as $key=>$value) {
							$new_labelCodeImage_add->setField($key,$value);
						}
						
						$new_labelCodeImage_result = $new_labelCodeImage_add->execute(); 
						
						if(FileMaker::isError($new_labelCodeImage_result)) fmsTrapError($new_labelCodeImage_result,"error.php"); 
						
						$new_labelCodeImage_row = current($new_labelCodeImage_result->getRecords());  
						
						// ASSIGN TO PDK -----------------------------------------------------------------------
						// CREATE VAR FOR NEW PDK_FK_LCI ------------------------------------------------------------
						$pdk_FK_lci = $new_labelCodeImage_row->getField('_PrimeLciIDX');
						
						// ADD NEW LABEL CODE IMAGE TO UPC (PDK_FK_LCI) ------------------------------------------------------------
						$edit_record_edit = $ContactLogin->newEditCommand('sup-UPC',$_REQUEST['-recid']);
						$edit_record_fields = array('JobUpcPdk::pdk_FK_lci'=>$pdk_FK_lci);
						foreach($edit_record_fields as $key=>$value) {
							$edit_record_edit->setField($key,$value);
						}
						
						$edit_record_result = $edit_record_edit->execute(); 
						
						if(FileMaker::isError($edit_record_result)) fmsTrapError($edit_record_result,"error.php"); 
						
						$edit_record_row = current($edit_record_result->getRecords());
						
						// REDIRECT ---------------------------------------------------------------
						//fmsRedirect($_REQUEST['redirect']);
						
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
	header("Location: " . $_REQUEST['redirect']);
}

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/php; charset=UTF-8">
<title>New Label Code Image</title>
<link href="css/oneColLiqCtr.css" rel="stylesheet" type="text/css" />
<link href="CSS/specStyle.css" rel="stylesheet" type="text/css" />

<!-- JAVASCRIPTS -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
</head>

<!-- <body class="oneColLiqCtr"> -->
<body class="oneColLiqCtr" onload="document.forms.error_form.submit();">

<form action="<?php echo $_SERVER['HTTP_REFERER'] ?>" method="post" name="error_form">
	<input name="infoReturned" type="hidden" value="<?php echo $errorVar; ?>" id="infoReturned" />
    <input name="infoPassFail" type="hidden" value="<?php echo $infoPassFail; ?>" id="infoPassFail" />
    <input name="new_item_name" type="hidden" value="<?php echo $new_labelCodeImage_row->getField('lciName'); ?>" id="new_item_name" />
    <input name="new_item_description" type="hidden" value="<?php echo $new_labelCodeImage_row->getField('lciDescription'); ?>" id="new_item_description" />
    <input name="new_item_file_name" type="hidden" value="<?php echo $new_labelCodeImage_row->getField('lciFileName'); ?>" id="new_item_file_name" />
</form>
</body>
</html>
