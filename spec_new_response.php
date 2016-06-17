<?php require_once('Connections/ContactLogin.php'); ?>
<?php

// CREATE NEW SPECIFICATION
$new_printerSpec_add = $ContactLogin->newAddCommand('sup-Spec');
$new_printerSpec_fields = array('Spe_ConIDX'=>$_REQUEST['Spe_ConIDX'],'speDocumentRasterEffects'=>$_REQUEST['speDocumentRasterEffects'],'speAbobeIllustratorTransparencyEffects'=>$_REQUEST['speAbobeIllustratorTransparencyEffects'],'speMaxNumColors4ColorNum'=>$_REQUEST['speMaxNumColors4ColorNum'],'speCoating'=>$_REQUEST['speCoating'],'speWhiteInk'=>$_REQUEST['speWhiteInk'],'speGradations'=>$_REQUEST['speGradations'],'speExtraStation'=>$_REQUEST['speExtraStation'],'speSubstrate'=>$_REQUEST['speSubstrate'],'speSubstrateOther'=>$_REQUEST['speSubstrateOther'],'spePrintProcess'=>$_REQUEST['spePrintProcess'],'spe_FK_cmp'=>$_REQUEST['p'],'speMaxNumColors'=>$_REQUEST['speMaxNumColors'],'speMaxNumColorsSpotNum'=>$_REQUEST['speMaxNumColorsSpotNum'],'speInkSequence'=>$_REQUEST['speInkSequence'],'spePrintSurfaceOrInside'=>$_REQUEST['spePrintSurfaceOrInside'],'speMinDot'=>$_REQUEST['speMinDot'],'speMinDotPerc'=>$_REQUEST['speMinDotPerc'],'speMinRuleWeightPositive'=>$_REQUEST['speMinRuleWeightPositive'],'speMinRuleWeightNegative'=>$_REQUEST['speMinRuleWeightNegative'],'speMinTypeSizePositive'=>$_REQUEST['speMinTypeSizePositive'],'speMinTypeSizeReverse'=>$_REQUEST['speMinTypeSizeReverse'],'speTolerance'=>$_REQUEST['speTolerance'],'speTrapsMinimum'=>$_REQUEST['speTrapsMinimum'],'speTrapsMaximum'=>$_REQUEST['speTrapsMaximum'],'speTrapsMetallic'=>$_REQUEST['speTrapsMetallic'],'speTrapsUnderColorCutBack'=>$_REQUEST['speTrapsUnderColorCutBack'],'speResolution'=>$_REQUEST['speResolution'],'speBleedsExternal'=>$_REQUEST['speBleedsExternal'],'speBleedsDustFlaps'=>$_REQUEST['speBleedsDustFlaps'],'speBleedsOverScore'=>$_REQUEST['speBleedsOverScore'],'speBleedsGlueFlaps'=>$_REQUEST['speBleedsGlueFlaps'],'speUPCBarcodeMinMagnification'=>$_REQUEST['speUPCBarcodeMinMagnification'],'speUPCBarcodeBWR'=>$_REQUEST['speUPCBarcodeBWR'],'speUPCBarcodeOrientation'=>$_REQUEST['speUPCBarcodeOrientation'],'speRichBlackTreatment'=>$_REQUEST['speRichBlackTreatment'],'speRichBlackTreatmentUnderColor'=>$_REQUEST['speRichBlackTreatmentUnderColor'],'speScalesMarks'=>$_REQUEST['speScalesMarks'],'speScalesMarksInstructions'=>$_REQUEST['speScalesMarksInstructions'],'speProofingReqProofType'=>$_REQUEST['speProofingReqProofType'],'speProofingReqProfileCurve'=>$_REQUEST['speProofingReqProfileCurve'],'SpeProofSubstrate'=>$_REQUEST['SpeProofSubstrate'],'speProofingReqScreening'=>$_REQUEST['speProofingReqScreening'],'speStepping'=>$_REQUEST['speStepping'],'speSteppingInstructions'=>$_REQUEST['speSteppingInstructions'],'speFinalFileType'=>$_REQUEST['speFinalFileType'],'speFtp'=>$_REQUEST['speFtp'],'speFtpHost'=>$_REQUEST['speFtpHost'],'speFtpUserID'=>$_REQUEST['speFtpUserID'],'speFtpPassword'=>$_REQUEST['speFtpPassword'],'speProofingReqScreeningInstructions'=>$_REQUEST['speProofingReqScreeningInstructions'],'speProofingReqProfileCurveInstructions'=>$_REQUEST['speProofingReqProfileCurveInstructions'],'speStatusComplete'=>$_REQUEST['statusComplete'],);
foreach($new_printerSpec_fields as $key=>$value) {
    $new_printerSpec_add->setField($key,$value);
}

$new_printerSpec_result = $new_printerSpec_add->execute(); 

if(FileMaker::isError($new_printerSpec_result)) fmsTrapError($new_printerSpec_result,"error.php"); 

$new_printerSpec_row = current($new_printerSpec_result->getRecords()); 

//fmsRedirect('printer.php?p=' . $_REQUEST['p']); 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
 
<?php

// VARS
$success_text = "New printer specification created";
$oURL = $_REQUEST['oURL'];
$passFail = "pass";
$printerURL = "printer.php?p=" . $_REQUEST['p'];
$specID =  $new_printerSpec_row->getField('_PrimeSpeIDX');
$specName =  $new_printerSpec_row->getField('speName');
$description = $_REQUEST['PsaDescription'];
$printerName =  $_REQUEST['printerName'];
$upload = $_REQUEST['upload'];

/*
if($oURL){
	$url = $oURL;
} else {
	$url = $printerURL;
} */

$redirect = ($oURL) ? $oURL : $printerURL;

// CREATE ATTACHMENT
if($upload == "TRUE"){

	// VARS ----------------------------
	
	$path_root = "../Printer Files/" . $printerName . "/";
	$path_spec = "../Printer Files/" . $printerName . "/" . $specName . "/";
	$uploaddir = "../Printer Files/" . $printerName . "/" . $specName . "/Attachments/";						// Upload directory: needs write permissions
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
						unset($_FILES['file']['tmp_name']);
						exit;
					}
				}
				// Get the extension from the filename.
				$ext = substr($_FILES['file']['name'], strrpos($_FILES['file']['name'],'.'), strlen($_FILES['file']['name'])-1);
				// Check if the filetype is allowed, if not DIE and inform the user.
				if(!in_array($ext,$allowed_filetypes)){
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
							// uploaded file was moved and renamed successfully. Display a message.
							$errorVar =  $success_text;
							$passFail = "pass";
							
							// CREATE NEW PSA RECORD ------------------------------------------------------------
							$new_psa_add = $ContactLogin->newAddCommand('sup-PrinterSpecAttachment');
							$new_psa_fields = array('PsaDescription'=>$description,'Psa_FK_spe'=>$specID,'PsaFileName'=>$_FILES["file"]["name"],);
							foreach($new_psa_fields as $key=>$value) {
								$new_psa_add->setField($key,$value);
							}
							$new_psa_result = $new_psa_add->execute();
							if(FileMaker::isError($new_psa_result)) fmsTrapError($new_psa_result,"error.php");
						} else {
							$errorVar =  "Error while uploading the file, Please contact Galileo.";
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
								$errorVar =  'No file was uploaded';
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
		header("Location: spec_new.php");
	}
	
}

?>

<!--HTML-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>New Printer Specification</title>

<script type="text/javascript">
	function redirect(){
		document.returnInfo.submit();
	}
</script>

</head>

<body onload="redirect()">

<form id="returnInfo" name="returnInfo" method="post" action="<?php echo $redirect; ?>">
  <input id="infoReturned" name="infoReturned" type="hidden" value="<?php echo $errorVar ?>">
  <input id="infoPassFail" name="infoPassFail" type="hidden" value="<?php echo $passFail ?>">
</form>

</body>
</html>
