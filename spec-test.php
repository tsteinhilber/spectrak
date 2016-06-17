<!-- CONNECTION -->
<?php require_once('Connections/ContactLogin.php'); ?>

<!-- FIND SUPPLIER -->
<?php
$Supplier_find = $ContactLogin->newFindCommand('sup-Supplier');
$Supplier_findCriterions = array('cmp_PK'=>'=='.fmsEscape($_SESSION['cmp_ID']),);
foreach($Supplier_findCriterions as $key=>$value) {
    $Supplier_find->AddFindCriterion($key,$value);
}

fmsSetPage($Supplier_find,'Supplier',1);

$Supplier_result = $Supplier_find->execute();

if(FileMaker::isError($Supplier_result)) fmsTrapError($Supplier_result,"error.php");

fmsSetLastPage($Supplier_result,'Supplier',1);

$Supplier_row = current($Supplier_result->getRecords());

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>


<!-- FIND CONTACTS -->
<?php
$Contacts_find = $ContactLogin->newFindCommand('sup-Contact');
$Contacts_findCriterions = array('con_FK_cmp'=>$_REQUEST['p'],);
foreach($Contacts_findCriterions as $key=>$value) {
    $Contacts_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contacts_find,'Contacts',50);

$Contacts_find->addSortRule('conNameL', 1, FILEMAKER_SORT_ASCEND);

$Contacts_result = $Contacts_find->execute();


// if(FileMaker::isError($Contacts_result)) fmsTrapError($Contacts_result,"error.php");
if(FileMaker::isError($Contacts_result)) $contact_error = 1 ;

fmsSetLastPage($Contacts_result,'Contacts',50);

if (!$contact_error){
	$Contacts_row = current($Contacts_result->getRecords());
	$Contacts__calc_portal = fmsRelatedRecord($Contacts_row, 'calc');
	$Contacts__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTER');
	$Contacts__SupJobCmpPRINTERConAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERConAdd');
	$Contacts__Sup_portal = fmsRelatedRecord($Contacts_row, 'Sup');
	$Contacts__SupJobCmpPRINTERAdd_portal = fmsRelatedRecord($Contacts_row, 'SupJobCmpPRINTERAdd');
}

$contact_error_class = $contact_error ? 'danger' : 'success';

?>


<!-- FIND ADDRESSES -->
<?php
$Addresses_find = $ContactLogin->newFindCommand('sup-Address');
$Addresses_findCriterions = array('add_FK_cmp'=>$_REQUEST['p'],);
foreach($Addresses_findCriterions as $key=>$value) {
    $Addresses_find->AddFindCriterion($key,$value);
}

fmsSetPage($Addresses_find,'Addresses',20);

$Addresses_result = $Addresses_find->execute();

// if(FileMaker::isError($Addresses_result)) fmsTrapError($Addresses_result,"error.php");
if(FileMaker::isError($Addresses_result)) $address_error = 1 ;

fmsSetLastPage($Addresses_result,'Addresses',20);

if (!$address_error){
	$Addresses_row = current($Addresses_result->getRecords());
}

?>

<? // VARIABLES

$company = $_REQUEST['com'];
$company_id = $_REQUEST['p'];

// for header
$title = $company . ": New Printer Specification"

?>


<!-- HTML -->
<html>

	<!-- HEAD -->
	<head>
		<meta http-equiv="x-ua-compatible" content="IE=8" >
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Bootstrap: Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	  <!-- Bootstrap: Optional theme -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<!-- JQuery -->
		<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
		<!-- Bootstrap: Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<!-- Custom: File Input -->
		<script src="js/file-input.js"></script>
		<link rel="stylesheet" href="css/new.css">
		<link rel="icon" type="image/gif" href="img/favicon.gif">
		<title>SpecTrak | <?php echo $company; ?>: New Specification</title>
	</head>

	<!-- BODY -->
	<body>

    <!-- HEADER -->
		<? include 'header.php'; ?>

		<div class="container">
      <!-- ROW -->
			<div class="row">

				<!-- COLUMN: LEFT -->
				<!-- <div class="col-md-6 col-sm-6">
          <h3>Error Status</h3>
          <hr>
          <p>Contact Error: <? echo $contact_error ?></p>
          <hr>
          <p>Address Error: <? echo $address_error ?></p>
        </div> -->

        <!-- COLUMN: RIGHT -->
				<div class="col-md-12 col-sm-12">
          <h3>Show/Hide</h3>
          <hr>
          <? include 'spec-contact.php'; ?>
        </div>

      </div>
    </div>

  </body>

</html>
