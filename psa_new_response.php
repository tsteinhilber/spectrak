<?php require_once('Connections/ContactLogin.php'); ?>

<?php

error_reporting(0);

// VARS ----------------------------

$backToDetail = $_REQUEST['backToDetail'];
$redirect = $_SERVER['HTTP_REFERER'];
$specID =  $_REQUEST['psa_FK_spe'];
$specName =  $_REQUEST['specName'];
$printerName =  $_REQUEST['printerName'];
$success_text = "New printer specification attachment created";

$path_root = "D:/Printer Files/" . $printerName . "/";
$path_spec = "D:/Printer Files/" . $printerName . "/" . $specName . "/";
$uploaddir = "D:/Printer Files/" . $printerName . "/" . $specName . "/Attachments/";						// Upload directory: needs write permissions
$log = "uploadlog.txt"; 																					// Upload LOG file
$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".exe", ".js",".html", ".htm", ".inc"); 	// what file types do you want to disallow?
$allowed_filetypes = array('.doc','.docx','.png','.gif','.jpg','.eps','.pdf','.ai','.zip', '.sitx'); 		// allowed filetypes

// DIRECTORY CREATION CODE ----------------------------------------------

mkdir($uploaddir, 0777, true);
if (is_dir($uploaddir)) {
    $directory_creation_comment = 'directory exists!';
	chmod($path_root, 0777);
	chmod($path_spec, 0777);
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
						$errorVar =  $success_text;
						$passFail = "pass";
						
						// CREATE NEW PSA RECORD ------------------------------------------------------------
						$new_psa_add = $ContactLogin->newAddCommand('sup-PrinterSpecAttachment');
						$new_psa_fields = array('PsaDescription'=>$_REQUEST['PsaDescription'],'Psa_FK_spe'=>$specID,'PsaFileName'=>$_FILES["file"]["name"],);
						foreach($new_psa_fields as $key=>$value) {
							$new_psa_add->setField($key,$value);
						}
						
						$new_psa_result = $new_psa_add->execute(); 
						
						if(FileMaker::isError($new_psa_result)) fmsTrapError($new_psa_result,"error.php"); 
						
						$new_psa_row = current($new_psa_result->getRecords());
						
						// NEW RECORD VARS ------------------------------------------------------
						$new_item_pk = $new_psa_row->getField('_PrimePsaIDX');
						$new_item_description = $new_psa_row->getField('PsaDescription');
						$new_item_file_name = $new_psa_row->getField('PsaFileName');
						
						
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
	header("Location: psa_new.php");
}

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/php; charset=UTF-8">
<title>New PSA Response</title>
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
  <input id="newItemDescription" name="newItemDescription" type="hidden" value="<?php echo $new_item_description; ?>">
  <input id="newItemFileName" name="newItemFileName" type="hidden" value="<?php echo $new_item_file_name; ?>">
  <input id="assigned_item" name="assigned_item" type="hidden" value="<?php echo $new_item_pk; ?>">
</form>

<div id="container" class="display_none">
  <div id="mainContent">

    <h1>New PSA</h1>
    
    <h2>Form Parameters</h2>
    <p><strong>PSA DESCRIPTION:</strong> <?php echo $_POST['PsaDescription']; ?></p>
    <p><strong>FILENAME:</strong> <?php echo $_FILES["file"]["name"]; ?></p>
    <p><strong>CWD:</strong> <?php echo getcwd(); ?></p>
    <p><strong>FILE:</strong><?php print_r($_FILES); ?>
    <p><strong>$cmpCompany:</strong> <?php echo $cmpCompany; ?></p>
    <p><strong>$path_root:</strong> <?php echo $path_root; ?></p>
    <p><strong>$uploaddir:</strong> <?php echo $uploaddir; ?></p>
    <p><strong>$directory_creation_comment:</strong> <?php echo $directory_creation_comment; ?></p>
    <br />
    <h2>New PSA Record</h2>
    <p><strong>IMAGE:</strong> <img alt="new symbol" src="<?php echo $new_psa_row->getField('PsaPath'); ?>" id="symbol" name="symbol"></p>
    <p><strong>DESCRIPTION:</strong> <?php echo $new_psa_row->getField('PsaDescription'); ?></p>
    <p><strong>FILE NAME:</strong> <?php echo $new_psa_row->getField('PsaFileName'); ?></p>
    <p><strong>CREATED BY:</strong> <?php echo $new_psa_row->getField('PsaZdevCreationAccountNameAuto'); ?></p>
    <p><strong>TIMESTAMP:</strong> <?php echo $new_psa_row->getField('PsaZdevCreationTimestampAuto'); ?></p>
  </div>
</div>
</body>
</html>
