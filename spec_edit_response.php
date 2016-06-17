<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$edit_spec_edit = $ContactLogin->newEditCommand('sup-Spec',$_REQUEST['-recid']);
$edit_spec_fields = array('speDocumentRasterEffects'=>$_REQUEST['speDocumentRasterEffects'],'speAbobeIllustratorTransparencyEffects'=>$_REQUEST['speAbobeIllustratorTransparencyEffects'],'speMaxNumColors4ColorNum'=>$_REQUEST['speMaxNumColors4ColorNum'],'speCoating'=>$_REQUEST['speCoating'],'speWhiteInk'=>$_REQUEST['speWhiteInk'],'speGradations'=>$_REQUEST['speGradations'],'speExtraStation'=>$_REQUEST['speExtraStation'],'speSubstrate'=>$_REQUEST['speSubstrate'],'speSubstrateOther'=>$_REQUEST['speSubstrateOther'],'spePrintProcess'=>$_REQUEST['spePrintProcess'],'speMaxNumColors'=>$_REQUEST['speMaxNumColors'],'speMaxNumColorsSpotNum'=>$_REQUEST['speMaxNumColorsSpotNum'],'speInkSequence'=>$_REQUEST['speInkSequence'],'spePrintSurfaceOrInside'=>$_REQUEST['spePrintSurfaceOrInside'],'speMinDot'=>$_REQUEST['speMinDot'],'speMinDotPerc'=>$_REQUEST['speMinDotPerc'],'speMinRuleWeightPositive'=>$_REQUEST['speMinRuleWeightPositive'],'speMinRuleWeightNegative'=>$_REQUEST['speMinRuleWeightNegative'],'speMinTypeSizePositive'=>$_REQUEST['speMinTypeSizePositive'],'speMinTypeSizeReverse'=>$_REQUEST['speMinTypeSizeReverse'],'speTolerance'=>$_REQUEST['speTolerance'],'speTrapsMinimum'=>$_REQUEST['speTrapsMinimum'],'speTrapsMaximum'=>$_REQUEST['speTrapsMaximum'],'speTrapsMetallic'=>$_REQUEST['speTrapsMetallic'],'speTrapsUnderColorCutBack'=>$_REQUEST['speTrapsUnderColorCutBack'],'speResolution'=>$_REQUEST['speResolution'],'speBleedsExternal'=>$_REQUEST['speBleedsExternal'],'speBleedsDustFlaps'=>$_REQUEST['speBleedsDustFlaps'],'speBleedsOverScore'=>$_REQUEST['speBleedsOverScore'],'speBleedsGlueFlaps'=>$_REQUEST['speBleedsGlueFlaps'],'speUPCBarcodeMinMagnification'=>$_REQUEST['speUPCBarcodeMinMagnification'],'speUPCBarcodeBWR'=>$_REQUEST['speUPCBarcodeBWR'],'speUPCBarcodeOrientation'=>$_REQUEST['speUPCBarcodeOrientation'],'speRichBlackTreatment'=>$_REQUEST['speRichBlackTreatment'],'speRichBlackTreatmentUnderColor'=>$_REQUEST['speRichBlackTreatmentUnderColor'],'speScalesMarks'=>$_REQUEST['speScalesMarks'],'speScalesMarksInstructions'=>$_REQUEST['speScalesMarksInstructions'],'speProofingReqProofType'=>$_REQUEST['speProofingReqProofType'],'speProofingReqProfileCurve'=>$_REQUEST['speProofingReqProfileCurve'],'SpeProofSubstrate'=>$_REQUEST['SpeProofSubstrate'],'speProofingReqScreening'=>$_REQUEST['speProofingReqScreening'],'speStepping'=>$_REQUEST['speStepping'],'speSteppingInstructions'=>$_REQUEST['speSteppingInstructions'],'speFinalFileType'=>$_REQUEST['speFinalFileType'],'speFtp'=>$_REQUEST['speFtp'],'speFtpHost'=>$_REQUEST['speFtpHost'],'speFtpUserID'=>$_REQUEST['speFtpUserID'],'speFtpPassword'=>$_REQUEST['speFtpPassword'],'speProofingReqScreeningInstructions'=>$_REQUEST['speProofingReqScreeningInstructions'],'speProofingReqProfileCurveInstructions'=>$_REQUEST['speProofingReqProfileCurveInstructions'],'speStatusComplete'=>$_REQUEST['statusComplete'],'Spe_ConIDX'=>$_REQUEST['Spe_ConIDX'],);
foreach($edit_spec_fields as $key=>$value) {
    $edit_spec_edit->setField($key,$value);
}

$edit_spec_result = $edit_spec_edit->execute(); 

if(FileMaker::isError($edit_spec_result)) fmsTrapError($edit_spec_result,"error.php"); 

$edit_spec_row = current($edit_spec_result->getRecords()); 
 
fmsRedirect('printer.php?p=' . $_POST['p']); 
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Edit Printer Spec</title>
<link href="css/oneColLiqCtr.css" rel="stylesheet" type="text/css" />
<link href="CSS/specStyle.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColLiqCtr">

<div id="container">
  <div id="mainContent">
    <h1> Edit  Spec
      Response<!-- end #mainContent -->
    </h1>
    <h2>form parameters</h2>
    <p><strong>p:</strong> <?php echo $_POST['p']; ?></p>
    <p><strong>Name:</strong> <?php echo $_POST['speName']; ?></p>
    <p><strong>Print Process:</strong> <?php echo $_POST['spePrintProcess']; ?></p>
    <p><strong>Substrate:</strong> <?php echo $_POST['speSubstrate']; ?></p>
    <p><strong>Max Num Colors:</strong> <?php echo $_POST['speMaxNumColors']; ?></p>
    <p><strong>Spot Color Num:</strong> <?php echo $_POST['speMaxNumColorsSpotNum']; ?></p>
    <p><strong>Surface of Inside:</strong> <?php echo $_POST['spePrintSurfaceOrInside']; ?></p>
    <p><strong>Coating:</strong> <?php echo $_POST['speCoating']; ?></p>
    <h2>Edited  Spec Record</h2>
    <p><strong>Printer:</strong> </p>
    <p><strong>Name:</strong> </p>
    <p><strong>Print Process:</strong> </p>
    <p><strong>Substrate:</strong> </p>
  </div>
</div>
</body>
</html>
