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

		<? include 'header.php'; ?>

		<div class="container">

     	<!-- ROW -->
    	<div class='row'>

	      <div class='hidden col-md-12'>
	        <div class="page-header">
	        	<h1>New Printer Spec <small>Printer: <?php echo $company ?> | ID: <?php echo $company_id ?></small></h1>
	        </div>
	      </div>

	      <!-- INFO PANEL -->
	      <div class='hidden col-md-12' id='info-panel'>
	        <div class="alert alert-<? echo $contact_error_class; ?>" role="alert">$contact_error: <?php echo (boolval($contact_error) ? 'true' : 'false') ?> | $address_error: <?php echo (boolval($address_error) ? 'true' : 'false') ?></div>
	      </div>

    	</div><!-- /row -->

			<!-- ROW -->
			<div class="row">

				<!-- COLUMN: NAV -->
				<div class="col-md-3">
					<ul class="nav nav-pills nav-stacked">
					  <li role="presentation"><a href="index.php">Home</a></li>
					  <li role="presentation"><a href="#">Logout</a></li>
					  <li role="presentation"><a href="#">Update Company Contact Info</a></li>
					  <li role="presentation"><a href="#">Jobs</a></li>
					</ul>

					<hr>

					<ul class="nav nav-pills nav-stacked">
					  <li role="presentation"><a href="#">Save</a></li>
					  <li role="presentation"><a href="#">Complete</a></li>
					  <li role="presentation"><a href="#">Cancel</a></li>
					</ul>
				</div>

				<!-- COLUMN: CONTENT -->
				<div class="col-md-9">

					<!-- PANEL: INSTRUCTIONS -->
					<div class="panel panel-info">
						<div class="panel-heading">Instructions</div>
						<div class="panel-body">
							<p>INSTRUCTIONS: Please enter item information in the fields below. Print Process, Substrate and Colors Allowed fields are required to Save. Select Save to if you would like to come back and complete the form at a later time. Select Complete if the information is complete.</p>
						</div>
					</div><!-- /panel -->

          <!-- CONTACT/ADDRESS INFO -->
          <div class="panel panel-default">
            <div class="panel-heading">Contact/Address Info</div>
            <div class="panel-body">
                <? if($address_error){ ?>
                  <p class="text-danger"><strong>New Address Form</strong></p>
                <? } else { ?>
                  <p class="text-success"><strong>Address Select</strong></p>
                <? } ?>
                <hr>
                <? if($contact_error){ ?>
                  <p class="text-danger"><strong>New Contact Form</strong></p>
                <? } else { ?>
                  <p class="text-success"><strong>Contact Select</strong></p>
                <? } ?>
            </div>
          </div>

					<form class="form-horizontal" action="spec_new_response.php" method="post" enctype="multipart/form-data" id="new_spec">

						<!-- PANEL: PRIMARY -->
						<div class="panel panel-default">
							<div class="panel-heading">General</div>
							<div class="panel-body">

								<!-- INPUT GROUP: spePrintProcess -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="spePrintProcess">Print Process</label>
							    <div class="col-sm-8">
											<select id="spePrintProcess" class="form-control">
												<option value="">Please choose...</option>
												<option disabled="disabled">—————————————</option>
												<option value="Offset Lithography">Offset Lithography</option>
												<option value="Flexography">Flexography</option>
												<option value="Dry Offset">Dry Offset</option>
												<option value="Gravure">Gravure</option>
												<option value="Flexo Heat Transfer">Flexo Heat Transfer</option>
												<option value="Digital Printing">Digital Printing</option>
												<option value="Other">Other</option>
											</select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speSubstrate -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speSubstrate">Substrate</label>
							    <div class="col-sm-8">
											<select id="speSubstrate" class="form-control">
												<option value="">Please choose...</option>
												<option disabled="disabled">—————————————</option>
												<option value="Paper">Paper</option>
												<option value="Board">Board</option>
												<option value="Clear Poly">Clear Poly</option>
												<option value="White Poly">White Poly</option>
												<option value="Plastic">Plastic</option>
												<option value="Foil">Foil</option>
												<option value="Heat Transfer Stock (wax coated)">Heat Transfer Stock (wax coated)</option>
												<option value="Other">Other</option>
											</select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMaxNumColors -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speMaxNumColors">Number of Colors</label>
							    <div class="col-sm-8">
											<select id="speMaxNumColors" class="form-control">
												<option value="">Please choose...</option>
												<option disabled="disabled">—————————————</option>
												<option value="4-color process ONLY">4-color process ONLY</option>
												<option value="6-color process (Hexachrome, Hi-Colour, Hi-Fi)">6-color process (Hexachrome, Hi-Colour, Hi-Fi)</option>
												<option value="Spot colors ONLY">Spot colors ONLY</option>
												<option value="4-color process plus">4-color process plus</option>
												<option value="7-color process (Opaltone)">7-color process (Opaltone)</option>
											</select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMaxNumColorsSpotNum -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMaxNumColorsSpotNum">Spot Colors (One Number)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMaxNumColorsSpotNum">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speInkSequence -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speInkSequence">Ink Sequence</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speInkSequence">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: spePrintSurfaceOrInside -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="spePrintSurfaceOrInside">Printing Location</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="spePrintSurfaceOrInside" value="Surface" /> Surface
											</label>
											<label class="btn btn-default">
												<input type="radio" id="spePrintSurfaceOrInside" value="Inside" /> Inside
											</label>
										</div>
										<span class="help-block">Are items printed on Surface or Inside?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speCoating -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speCoating">Coating</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speCoating" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speCoating" value="No" /> No
											</label>
										</div>
										<span class="help-block">Is there a Varnish or Aqueous Coating on this item?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speWhiteInk -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speWhiteInk">White Ink</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speWhiteInk" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speWhiteInk" value="No" /> No
											</label>
										</div>
										<span class="help-block">Is White Ink required on this item?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speGradations -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speGradations">Gradations</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speGradations" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speGradations" value="No" /> No
											</label>
										</div>
										<span class="help-block">Are gradations feasible with this printing method?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMinDot -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinDot">Minimum Dot</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speMinDot" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speMinDot" value="No" /> No
											</label>
										</div>
										<span class="help-block">Is there a minimum dot required?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMinDotPerc -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinDotPerc">What is the minimum dot? (%)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMinDotPerc">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speDocumentRasterEffects -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speDocumentRasterEffects">Minimum Dot</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speDocumentRasterEffects" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speDocumentRasterEffects" value="No" /> No
											</label>
										</div>
										<span class="help-block">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle document raster effects?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speAbobeIllustratorTransparencyEffects -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speAbobeIllustratorTransparencyEffects">Minimum Dot</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speAbobeIllustratorTransparencyEffects" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speAbobeIllustratorTransparencyEffects" value="No" /> No
											</label>
										</div>
										<span class="help-block">If Galileo Global Branding Group, Inc. is not completing prepress, can your prepress provide handle Adobe Illustrator transparency effects?</span>
							    </div>
							  </div><!-- /group -->

							</div><!-- /panel-body -->
						</div><!-- /panel -->

						<!-- PANEL: PRINT LIMITATIONS -->
						<div class="panel panel-default">
							<div class="panel-heading">Print Limitations</div>
							<div class="panel-body">

								<!-- INPUT GROUP: speMinRuleWeightPositive -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinRuleWeightPositive">Min. Rule Weight: Positive (pt)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMinRuleWeightPositive">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMinRuleWeightNegative -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinRuleWeightNegative">Min. Rule Weight: Negative (pt)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMinRuleWeightNegative">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMinTypeSizePositive -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinTypeSizePositive">Min. Type Size: Positive Copy (pt)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMinTypeSizePositive">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speMinTypeSizeReverse -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speMinTypeSizeReverse">Min. Type Size: Reverse Copy (pt)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speMinTypeSizeReverse">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speTolerance -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speTolerance">Tolerance</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speTolerance">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							      <span class="help-block">Tolerance: Copy clearance from trims &amp; folds</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speTrapsMinimum -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speTrapsMinimum">Traps Minimum (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speTrapsMinimum">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speTrapsMetallic -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speTrapsMetallic">Traps Metallic (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speTrapsMetallic">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speTrapsUnderColorCutBack -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speTrapsUnderColorCutBack">Traps Under Color Cut-Back (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speTrapsUnderColorCutBack">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speResolution -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speResolution">Resolution: Lines per Inch</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speResolution">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speBleedsExternal -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speBleedsExternal">Bleeds: External (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speBleedsExternal">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speBleedsDustFlaps -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speBleedsDustFlaps">Bleeds: Dust Flaps (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speBleedsDustFlaps">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speBleedsOverScore -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speBleedsOverScore">Bleeds: Over Score (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speBleedsOverScore">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speBleedsGlueFlaps -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speBleedsGlueFlaps">Bleeds: Glue Flaps (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speBleedsGlueFlaps">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speUPCBarcodeMinMagnification -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speUPCBarcodeMinMagnification">UPC Barcode Min. Magnification (%)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speUPCBarcodeMinMagnification">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speUPCBarcodeBWR -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speUPCBarcodeBWR">UPC Barcode BWR (inches)</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speUPCBarcodeBWR">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speUPCBarcodeOrientation -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speUPCBarcodeOrientation">UPC Barcode Orientation</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speUPCBarcodeOrientation">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speRichBlackTreatment -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speRichBlackTreatment">Rich Black Treatment</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speRichBlackTreatment" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speRichBlackTreatment" value="No" /> No
											</label>
										</div>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speRichBlackTreatmentUnderColor -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speRichBlackTreatmentUnderColor">What is the uner color?</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speRichBlackTreatmentUnderColor">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speScalesMarks -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speScalesMarks">Special Scales</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speScalesMarks" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speScalesMarks" value="No" /> No
											</label>
										</div>
										<span class="help-block">Do special scales or marks need to be added?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speScalesMarksInstructions -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speScalesMarksInstructions">If yes, please include instructions</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speScalesMarksInstructions">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

							</div><!-- /panel-body -->
						</div><!-- /panel -->

						<!-- PANEL: PROOFING REQUIREMENTS -->
						<div class="panel panel-default">
							<div class="panel-heading">Proofing Requirements</div>
							<div class="panel-body">
								<p>If necessary, Galileo Global Branding Group, Inc. will fingerprint press before prepress begins.</p>
								<hr>

								<!-- INPUT GROUP: speProofingReqProofType -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speProofingReqProofType">Type</label>
							    <div class="col-sm-8">
                    <select id="speProofingReqProofType" class="form-control">
                      <option value="">Please choose...</option>
                      <option disabled="disabled">—————————————</option>
                      <option value="Color Calibrated Epson">Color Calibrated Epson</option>
                      <option value="Kodak Approval">Kodak Approval</option>
                      <option value="Other">Other</option>
                    </select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speProofingReqProfileCurve -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speProofingReqProfileCurve">Profile/Curve</label>
							    <div class="col-sm-8">
                    <select id="speProofingReqProfileCurve" class="form-control">
                      <option value="">Please choose...</option>
                      <option disabled="disabled">—————————————</option>
                      <option value="Standard SWOP">Standard SWOP</option>
                      <option value="F.I.R.S.T.">F.I.R.S.T.</option>
                      <option value="Printer Specific">Printer Specific</option>
                    </select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speProofingReqProfileCurveInstructions -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speProofingReqProfileCurveInstructions">If yes, please include instructions</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speProofingReqProfileCurveInstructions">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: SpeProofSubstrate -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="SpeProofSubstrate">Substrate</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="SpeProofSubstrate">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speProofingReqScreening -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speProofingReqScreening">Screening</label>
							    <div class="col-sm-8">
                    <select id="speProofingReqScreening" class="form-control">
                      <option value="">Please choose...</option>
                      <option disabled="disabled">—————————————</option>
                      <option value="Standard(C75 M45 Y90 K15 - round dot)">Standard(C75 M45 Y90 K15 - round dot)</option>
                      <option value="Printer Specific">Printer Specific</option>
                    </select>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speProofingReqScreeningInstructions -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speProofingReqScreeningInstructions">If yes, please include instructions</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speProofingReqScreeningInstructions">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speStepping -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speStepping">Will this job require stepping?</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speStepping" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speStepping" value="No" /> No
											</label>
										</div>
										<span class="help-block">Will this job require stepping?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speSteppingInstructions -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speSteppingInstructions">If yes, please include instructions</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speSteppingInstructions">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speFinalFileType -->
							  <div class="form-group">
							    <label class="control-label col-sm-4" for="speFinalFileType">Final File Type</label>
							    <div class="col-sm-8">
                    <select id="speFinalFileType" class="form-control">
	                    <option value="">Please choose...</option>
	                    <option value="Hi-Res Certified PDF">Hi-Res Certified PDF</option>
	                    <option value="DCS2 (single file)">DCS2 (single file)</option>
	                    <option value="DCS2 (multi file)">DCS2 (multi file)</option>
	                    <option value="1-bitt TIFF">1-bitt TIFF</option>
	                    <option value="Native ESKO">Native ESKO</option>
	                    <option value="Native Illustrator">Native Illustrator</option>
                    </select>
							    </div>
							  </div><!-- /group -->

							</div><!-- /panel-body -->
						</div><!-- /panel -->

						<!-- PANEL: FTP -->
						<div class="panel panel-default">
							<div class="panel-heading">FTP</div>
							<div class="panel-body">

								<!-- INPUT GROUP: speFtp -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speFtp">FTP Accepted</label>
									<div class="col-md-8">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-default">
												<input type="radio" id="speFtp" value="Yes" /> Yes
											</label>
											<label class="btn btn-default">
												<input type="radio" id="speFtp" value="No" /> No
											</label>
										</div>
										<span class="help-block">Do you accept final file delivery via FTP?</span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speFtpHost -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speFtpHost">Host</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speFtpHost">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speFtpUserID -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speFtpUserID">User ID</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speFtpUserID">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: speFtpPassword -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="speFtpPassword">Password</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="speFtpPassword">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

							</div><!-- /panel-body -->
						</div><!-- /panel -->

						<!-- PANEL: ATTACHEMENT -->
						<div class="panel panel-default">
							<div class="panel-heading">Attachment</div>
							<div class="panel-body">

								<!-- INPUT GROUP: PsaDescription -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="PsaDescription">Description</label>
							    <div class="col-sm-8">
							      <input type="text" class="form-control" id="PsaDescription">
							      <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    </div>
							  </div><!-- /group -->

								<!-- INPUT GROUP: File -->
							  <div class="form-group has-feedback">
							    <label class="control-label col-sm-4" for="PsaFile">File</label>
							    <div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-btn">
												<span class="btn btn-default btn-file">
													Browse&hellip; <input type="file" id="file">
												</span>
											</span>
											<input type="text" class="form-control" readonly>
										</div>
							    </div>
							  </div><!-- /group -->

							</div><!-- /panel-body -->
						</div><!-- /panel -->

            <input type="hidden" name="statusComplete" value="" id="statusComplete">
            <input type="hidden" name="oURL" value="<?php echo $oURL; ?>" id="oURL">
            <input type="hidden" name="specID" value="new" id="specID">
            <input type="hidden" name="cmdupload" value="Save" />
            <input type="hidden" name="MAX_FILE_SIZE" value="6000000">
            <input type="hidden" name="printerName" value="<?php echo $_REQUEST['com']; ?>" id="printerName">
            <input type="hidden" name="p" value="<?php echo $_REQUEST['p']; ?>" id="p">
            <input type="hidden" name="upload" value="" id="upload">
            <input type="button" name="new_spec_save" value="Save" onclick="validateSave()" class='btn btn-default' />
            <input type="button" name="new_spec_submit" value="Complete" onclick="validateSubmit()" class='btn btn-default' />

					</form><!--  /form -->

				</div><!-- /column -->

			</div><!-- /row -->

		</div>

	</body>
</html>
