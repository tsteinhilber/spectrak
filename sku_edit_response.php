<?php require_once('Connections/ContactLogin.php'); ?>

<?php
/*
print_r($_REQUEST['pdkPackageConfiguration']);
echo "<BR><BR>";
print_r($pdkPackageConfiguration);
echo $_REQUEST['u'];
die(); */
error_reporting(0);

$pdkHealthClaims = "";
$pdkPackageConfiguration = "";
$pdkAllergensInProduct = "";
$pdkAllergensInFacility = "";

if(isset($_REQUEST["pdkHealthClaims"])){
	$pdkHealthClaims = $_REQUEST["pdkHealthClaims"];
}

if(isset($_REQUEST["pdkPackageConfiguration"])){
	$pdkPackageConfiguration = $_REQUEST["pdkPackageConfiguration"];
}
if(isset($_REQUEST["pdkAllergensInProduct"])){
	$pdkAllergensInProduct = $_REQUEST["pdkAllergensInProduct"];
}
if(isset($_REQUEST["pdkAllergensInFacility"])){
	$pdkAllergensInFacility = $_REQUEST["pdkAllergensInFacility"];
}

$pdkHealthClaims = implode("\r", $pdkHealthClaims);
$pdkPackageConfiguration = implode("\r", $pdkPackageConfiguration);
$pdkAllergensInProduct = implode("\r", $pdkAllergensInProduct);
$pdkAllergensInFacility = implode("\r", $pdkAllergensInFacility);

$skuInfoPassFail = "pass";
if($_REQUEST['status'] == "Submitted") {
	$skuInfoReturned = "SKU submission successful";
} else {
	$skuInfoReturned = "SKU save successful";
}

if($_REQUEST['redirect']){
	$sku = $_SERVER['HTTP_REFERER'];
	$redirect = $_REQUEST['redirect'];
} else {
	$sku = $_REQUEST['redirect'];
	$redirect = $_SERVER['HTTP_REFERER'] . "&skuInfoPassFail=" . $skuInfoPassFail . "&skuInfoReturned=" . $skuInfoReturned;	
}

?>

<?php
$edit_record_edit = $ContactLogin->newEditCommand('sup-PDK',$_REQUEST['-recid']);
$edit_record_fields = array('pdkServingSizeAsPackaged'=>$_REQUEST['pdkServingSizeAsPackaged'],
'pdkServingSizeAsPrepared'=>$_REQUEST['pdkServingSizeAsPrepared'],
'pdkServingsPerContainerAsPackaged'=>$_REQUEST['pdkServingsPerContainerAsPackaged'],
'pdkServingsPerContainerAsPrepared'=>$_REQUEST['pdkServingsPerContainerAsPrepared'],
'pdkCaloriesAsPackaged'=>$_REQUEST['pdkCaloriesAsPackaged'],
'pdkCaloriesAsPrepared'=>$_REQUEST['pdkCaloriesAsPrepared'],
'pdkCaloriesFromFatAsPackaged'=>$_REQUEST['pdkCaloriesFromFatAsPackaged'],
'pdkCaloriesFromFatAsPrepared'=>$_REQUEST['pdkCaloriesFromFatAsPrepared'],
'pdkTotalFatAsPackagedG'=>$_REQUEST['pdkTotalFatAsPackagedG'],
'pdkTotalFatAsPackagedPerc'=>$_REQUEST['pdkTotalFatAsPackagedPerc'],
'pdkTotalFatAsPreparedG'=>$_REQUEST['pdkTotalFatAsPreparedG'],
'pdkTotalFatAsPreparedPerc'=>$_REQUEST['pdkTotalFatAsPreparedPerc'],
'pdkSaturatedFatAsPackagedG'=>$_REQUEST['pdkSaturatedFatAsPackagedG'],
'pdkSaturatedFatAsPackagedPerc'=>$_REQUEST['pdkSaturatedFatAsPackagedPerc'],
'pdkSaturatedFatAsPreparedG'=>$_REQUEST['pdkSaturatedFatAsPreparedG'],
'pdkSaturatedFatAsPreparedPerc'=>$_REQUEST['pdkSaturatedFatAsPreparedPerc'],
'pdkTransFatAsPackagedG'=>$_REQUEST['pdkTransFatAsPackagedG'],
'pdkTransFatAsPreparedG'=>$_REQUEST['pdkTransFatAsPreparedG'],
'pdkDietaryFiberAsPackagedG'=>$_REQUEST['pdkDietaryFiberAsPackagedG'],
'pdkDietaryFiberAsPackagedPerc'=>$_REQUEST['pdkDietaryFiberAsPackagedPerc'],
'pdkDietaryFiberAsPreparedG'=>$_REQUEST['pdkDietaryFiberAsPreparedG'],
'pdkDietaryFiberAsPreparedPerc'=>$_REQUEST['pdkDietaryFiberAsPreparedPerc'],
'pdkSugarsAsPackagedG'=>$_REQUEST['pdkSugarsAsPackagedG'],
'pdkSugarsAsPreparedG'=>$_REQUEST['pdkSugarsAsPreparedG'],
'pdkProteinAsPackagedG'=>$_REQUEST['pdkProteinAsPackagedG'],
'pdkProteinAsPackagedPerc'=>$_REQUEST['pdkProteinAsPackagedPerc'],
'pdkProteinAsPreparedG'=>$_REQUEST['pdkProteinAsPreparedG'],
'pdkProteinAsPreparedPerc'=>$_REQUEST['pdkProteinAsPreparedPerc'],
'pdkVitaminAAsPackaged'=>$_REQUEST['pdkVitaminAAsPackaged'],
'pdkVitaminAAsPrepared'=>$_REQUEST['pdkVitaminAAsPrepared'],
'pdkVitaminCAsPackaged'=>$_REQUEST['pdkVitaminCAsPackaged'],
'pdkVitaminCAsPrepared'=>$_REQUEST['pdkVitaminCAsPrepared'],
'pdkCalciumAsPackagedPer'=>$_REQUEST['pdkCalciumAsPackagedPer'],
'pdkCalciumAsPreparedPer'=>$_REQUEST['pdkCalciumAsPreparedPer'],
'pdkIronAsPackagedPer'=>$_REQUEST['pdkIronAsPackagedPer'],
'pdkIronAsPreparedPer'=>$_REQUEST['pdkIronAsPreparedPer'],
'pdkVitaminDAsPackagedPer'=>$_REQUEST['pdkVitaminDAsPackagedPer'],
'pdkVitaminDAsPreparedPer'=>$_REQUEST['pdkVitaminDAsPreparedPer'],
'pdkThiaminAsPackaged'=>$_REQUEST['pdkThiaminAsPackaged'],
'pdkThiaminAsPrepared'=>$_REQUEST['pdkThiaminAsPrepared'],
'pdkRiboflavinAsPackaged'=>$_REQUEST['pdkRiboflavinAsPackaged'],
'pdkRiboflavinAsPrepared'=>$_REQUEST['pdkRiboflavinAsPrepared'],
'pdkNiacinAsPackaged'=>$_REQUEST['pdkNiacinAsPackaged'],
'pdkNiacinAsPrepared'=>$_REQUEST['pdkNiacinAsPrepared'],
'pdkVitaminB12AsPackaged'=>$_REQUEST['pdkVitaminB12AsPackaged'],
'pdkVitaminB12AsPrepared'=>$_REQUEST['pdkVitaminB12AsPrepared'],
'pdkFolicAcidAsPackaged'=>$_REQUEST['pdkFolicAcidAsPackaged'],
'pdkFolicAcidAsPrepared'=>$_REQUEST['pdkFolicAcidAsPrepared'],
'pdkVitaminB6AsPackaged'=>$_REQUEST['pdkVitaminB6AsPackaged'],
'pdkVitaminB6AsPrepared'=>$_REQUEST['pdkVitaminB6AsPrepared'],
'pdkPhosphorusAsPackaged'=>$_REQUEST['pdkPhosphorusAsPackaged'],
'pdkPhosphorusAsPrepared'=>$_REQUEST['pdkPhosphorusAsPrepared'],
'pdkMagnesiumAsPackaged'=>$_REQUEST['pdkMagnesiumAsPackaged'],
'pdkMagnesiumAsPrepared'=>$_REQUEST['pdkMagnesiumAsPrepared'],
'pdkZincAsPackaged'=>$_REQUEST['pdkZincAsPackaged'],
'pdkZincAsPrepared'=>$_REQUEST['pdkZincAsPrepared'],
'pdkCholesterolAsPackagedMG'=>$_REQUEST['pdkCholesterolAsPackagedMG'],
'pdkCholesterolAsPackagedPerc'=>$_REQUEST['pdkCholesterolAsPackagedPerc'],
'pdkCholesterolAsPreparedMG'=>$_REQUEST['pdkCholesterolAsPreparedMG'],
'pdkCholesterolAsPreparedPerc'=>$_REQUEST['pdkCholesterolAsPreparedPerc'],
'pdkSodiumAsPackagedMG'=>$_REQUEST['pdkSodiumAsPackagedMG'],
'pdkSodiumAsPackagedPerc'=>$_REQUEST['pdkSodiumAsPackagedPerc'],
'pdkSodiumAsPreparedMG'=>$_REQUEST['pdkSodiumAsPreparedMG'],
'pdkSodiumAsPreparedPerc'=>$_REQUEST['pdkSodiumAsPreparedPerc'],
'pdkTotalCarbohydrateAsPackagedG'=>$_REQUEST['pdkTotalCarbohydrateAsPackagedG'],
'pdkTotalCarbohydrateAsPackagedPerc'=>$_REQUEST['pdkTotalCarbohydrateAsPackagedPerc'],
'pdkTotalCarbohydrateAsPreparedG'=>$_REQUEST['pdkTotalCarbohydrateAsPreparedG'],
'pdkTotalCarbohydrateAsPreparedPerc'=>$_REQUEST['pdkTotalCarbohydrateAsPreparedPerc'],
'pdkPotassiumAsPackagedMG'=>$_REQUEST['pdkPotassiumAsPackagedMG'],
'pdkPotassiumAsPackagedPerc'=>$_REQUEST['pdkPotassiumAsPackagedPerc'],
'pdkPotassiumAsPreparedMG'=>$_REQUEST['pdkPotassiumAsPreparedMG'],
'pdkPotassiumAsPreparedPerc'=>$_REQUEST['pdkPotassiumAsPreparedPerc'],
'pdkProductName'=>$_REQUEST['pdkProductName'],
'pdkNetWeight'=>$_REQUEST['pdkNetWeight'],
'pdkSecondaryDescriptors'=>$_REQUEST['pdkSecondaryDescriptors'],
'pdkAvailableTrademarks'=>$_REQUEST['pdkAvailableTrademarks'],
'pdkProductClaims'=>$_REQUEST['pdkProductClaims'],
'pdkActiveNonActiveIngredients'=>$_REQUEST['pdkActiveNonActiveIngredients'],
'pdkCompareToStatement'=>$_REQUEST['pdkCompareToStatement'],
'pdkIngredientStatement'=>$_REQUEST['pdkIngredientStatement'],
'pdkCountryOfOrigin'=>$_REQUEST['pdkCountryOfOrigin'],
'pdkProductUseStorage'=>$_REQUEST['pdkProductUseStorage'],
'pdkPackageComponents'=>$_REQUEST['pdkPackageComponents'],
'pdkPackageConfiguration'=>$pdkPackageConfiguration,'pdkProductPreparationInstructions'=>$_REQUEST['pdkProductPreparationInstructions'],
'pdkRecipe'=>$_REQUEST['pdkRecipe'],'pdkWarningStatements'=>$_REQUEST['pdkWarningStatements'],
'pdkSpecialSymbolsRequired'=>$_REQUEST['pdkSpecialSymbolsRequired'],
'pdkSpecialSymbolsSpecifySymbol'=>$_REQUEST['pdkSpecialSymbolsSpecifySymbol'],
'pdkSpecialSymbolsArtworkProvided'=>$_REQUEST['pdkSpecialSymbolsArtworkProvided'],
'pdkTestedOnAnimals'=>$_REQUEST['pdkTestedOnAnimals'],
'pdkProductCertifiedEnvironmental'=>$_REQUEST['pdkProductCertifiedEnvironmental'],
'pdkProductSustainablePackaging'=>$_REQUEST['pdkProductSustainablePackaging'],
'pdkProductPackagingBiodegradable'=>$_REQUEST['pdkProductPackagingBiodegradable'],
'pdkDescription'=>$_REQUEST['pdkDescription'],'pdkExample'=>$_REQUEST['pdkExample'],
'pdkMethodOfApplication'=>$_REQUEST['pdkMethodOfApplication'],
'pdkMayContain'=>$_REQUEST['pdkMayContain'],
'pdkProductCertifiedEnvironmentalArtwork'=>$_REQUEST['pdkProductCertifiedEnvironmentalArtwork'],
'pdkProductCertifiedEnvironmentalStatement'=>$_REQUEST['pdkProductCertifiedEnvironmentalStatement'],
'JobUpc::upcPDKStatus'=>$_REQUEST['status'],
'pdkOrganic'=>$_REQUEST['pdkOrganic'],
'pdkAllergensInProduct'=>$pdkAllergensInProduct,
'pdkAllergensInFacility'=>$pdkAllergensInFacility,
'pdkPolyunsaturatedFatAsPackagedG'=>$_REQUEST['pdkPolyunsaturatedFatAsPackagedG'],
'pdkPolyunsaturatedFatAsPreparedG'=>$_REQUEST['pdkPolyunsaturatedFatAsPreparedG'],
'pdkMonounsaturatedFatAsPackagedG'=>$_REQUEST['pdkMonounsaturatedFatAsPackagedG'],
'pdkMonounsaturatedFatAsPreparedG'=>$_REQUEST['pdkMonounsaturatedFatAsPreparedG'],
'pdkVitaminEAsPackaged'=>$_REQUEST['pdkVitaminEAsPackaged'],
'pdkVitaminEAsPrepared'=>$_REQUEST['pdkVitaminEAsPrepared'],
'pdkAsPreparedFootnote'=>$_REQUEST['pdkAsPreparedFootnote'],
'pdkCOOL'=>$_REQUEST['pdkCOOL'],
'pdkUsdaNum'=>$_REQUEST['pdkUsdaNum'],
'pdkProductClaimsOptional'=>$_REQUEST['pdkProductClaimsOptional'],
'pdkProductCertified3rdParty'=>$_REQUEST['pdkProductCertified3rdParty'],
'pdkHealthClaims'=>$pdkHealthClaims,
'pdkFlavoringRequired'=>$_REQUEST['pdkFlavoringRequired'],
'pdkFlavoring'=>$_REQUEST['pdkFlavoring'],
'pdkWarningStatementsBackSidePanel'=>$_REQUEST['pdkWarningStatementsBackSidePanel'],
'pdkExistingImagery'=>$_REQUEST['pdkExistingImagery'],

);
foreach($edit_record_fields as $key=>$value) {
    $edit_record_edit->setField($key,$value);
}

// RUN SCRIPT
$script_param = $_REQUEST['u'];
$edit_record_edit->SetScript('trigger [update job_SpecStatus]',$script_param);

$edit_record_result = $edit_record_edit->execute(); 

if(FileMaker::isError($edit_record_result)) fmsTrapError($edit_record_result,"error.php");



//$edit_record_row = current($edit_record_result->getRecords()); 

//echo "<B>REDIRECT:</B> " . $redirect . "<BR><B>SKU:</B> " . $sku;

// INSERT FMP SCRIPT CODE HERE ----------------------------------------

fmsRedirect($redirect); 
 
// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
pdkHealthClaims = <?php print_r( $_REQUEST['pdkHealthClaims'] ) ?>
</body>
</html>
