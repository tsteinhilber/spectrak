<form class="form-horizontal" action="spec_new_response.php" method="post" enctype="multipart/form-data" id="new_spec">

<? if($contact_error){ ?>
  <!-- <p><strong>No Contacts: Show Form</strong></p> -->

  <!-- INPUT GROUP: Contact First Name -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="contact_name_first">First Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="contact_name_first">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

  <!-- INPUT GROUP: Contact Last Name -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="contact_name_last">Last Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="contact_name_last">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

  <!-- INPUT GROUP: Contact Title -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="contact_title">Title</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="contact_title">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

  <!-- INPUT GROUP: Contact Email -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="contact_email">Email</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="contact_email">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

<? } else { ?>
  <!-- <p><strong>Contact Select</strong></p> -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="speMinRuleWeightPositive">Contact</label>
    <div class="col-sm-8">
      <select name="Spe_ConIDX" id="Spe_ConIDX" class="form-control">
        <option value="" disabled selected>Choose Contact...</option>
        <option disabled="disabled">—————————————</option>
        <option value="new">Add New Contact</option>
        <option disabled="disabled">—————————————</option>
        <?php foreach($Contacts_result->getRecords() as $Contacts_row){ ?>
        <option value="<?php echo $Contacts_row->getField('con_PK'); ?>" <?php echo $selected ?> ><?php echo $Contacts_row->getField('conNameF'); ?> <?php echo $Contacts_row->getField('conNameL'); ?></option>
        <?php } ?>
      </select>
    </div>
  </div><!-- /group -->

<? } ?>

<? if($address_error){ ?>
  <!-- <p><strong>No Addresses: Show Form</strong></p> -->
  <hr>

  <!-- INPUT GROUP: Street Address -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="address_street">Street Address</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="address_street">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

  <!-- INPUT GROUP: City -->
  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="address_city">City</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="address_city">
      <!-- <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> -->
    </div>
  </div><!-- /group -->

<? } else { ?>
  <!-- <p><strong>Address Select</strong></p> -->

  <div class="form-group has-feedback">
    <label class="control-label col-sm-4" for="speMinRuleWeightPositive">Address</label>
    <div class="col-sm-8">
      <select name="con_FK_add" id="con_FK_add" class="form-control">
        <option value="" disabled selected>Choose Address...</option>
        <option disabled="disabled">—————————————</option>
        <option value="new">Add New Address</option>
        <option disabled="disabled">—————————————</option>
        <? foreach($Addresses_result->getRecords() as $Add_row){ ?>
          <option value="<?php echo $Add_row->getField('add_PK'); ?>">
            <?php echo $Add_row->getField('addAddress'); ?> <?php echo $Add_row->getField('addCityStateZip'); ?>
          </option>
        <? } ?>
      </select>
    </div>
  </div><!-- /group -->

<? } ?>

</form>
