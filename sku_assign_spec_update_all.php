<?php require_once('Connections/ContactLogin.php'); ?>
<?php

$parameters = $_REQUEST['job_PK'] . ' ' . $_REQUEST['upc_FK_spe'];
$cmd = $ContactLogin->newPerformScriptCommand('sup-UPC', 'Assign Spec to all Job UPC', $parameters);
$result = $cmd->execute();

fmsRedirect($_REQUEST['sku']);

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Update UPC Spec</title>
</head>

<body>
<p><strong>UPC_FK_spe:</strong> <?php print_r($_POST['upc_FK_spe']); ?></p>
<p><strong>Job_PK:</strong> <?php echo $_POST['job_PK']; ?></p>
<p><strong>UPC_PK:</strong> <?php echo $_POST['upc_PK']; ?></p>
<p><strong>RecID:</strong> <?php echo $_POST['-recid']; ?></p>
<p><strong>$parameters:</strong> <?php echo $parameters; ?></p>
</body>
</html>
