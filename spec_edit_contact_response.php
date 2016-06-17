<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$edit_spec_edit = $ContactLogin->newEditCommand('sup-Spec',$_REQUEST['-recid']);
$edit_spec_fields = array('Spe_ConIDX'=>$_REQUEST['contactID'],);
foreach($edit_spec_fields as $key=>$value) {
    $edit_spec_edit->setField($key,$value);
}

$edit_spec_result = $edit_spec_edit->execute(); 

if(FileMaker::isError($edit_spec_result)) fmsTrapError($edit_spec_result,"error.php"); 

$edit_spec_row = current($edit_spec_result->getRecords()); 
 
$printerURL = 'printer.php?p=' . $_REQUEST['p'];
$specID = $_REQUEST['specID'];
$specURL = 'spec.php?s=' . $specID;
$url = '';

if($specID){
	$url = $specURL;
} else {
	$url = $printerURL;
}
 
fmsRedirect($url);
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Edit Printer Spec</title>
</head>

<body>
    <h1>Edit Spec Response</h1>
    <h2>form parameters</h2>
    <p><strong>p:</strong> <?php echo $_POST['p']; ?></p>
    <p><strong>Name:</strong> <?php echo $_POST['speName']; ?></p>
    <p><strong>Print Process:</strong> <?php echo $_POST['spePrintProcess']; ?></p>
    <p><strong>Substrate:</strong> <?php echo $_POST['speSubstrate']; ?></p>
    <p><strong>Max Num Colors:</strong> <?php echo $_POST['speMaxNumColors']; ?></p>
    <p><strong>Spot Color Num:</strong> <?php echo $_POST['speMaxNumColorsSpotNum']; ?></p>
    <p><strong>Surface of Inside:</strong> <?php echo $_POST['spePrintSurfaceOrInside']; ?></p>
    <p><strong>Coating:</strong> <?php echo $_POST['speCoating']; ?></p>
    <h2>Edited Spec Record</h2>
    <p><strong>Printer:</strong></p>
    <p><strong>Name:</strong></p>
    <p><strong>Print Process:</strong></p>
    <p><strong>Substrate:</strong></p>
</body>
</html>
