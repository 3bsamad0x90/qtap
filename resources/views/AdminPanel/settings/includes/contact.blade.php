<!-- form -->
<div class="row">

  <div class="col-12 col-md-4">
    <label class="form-label" for="contactusEmail">البريد الإلكتروني</label>
    {{Form::text('contactusEmail',getSettingValue('contactusEmail'),['id'=>'contactusEmail','class'=>'form-control'])}}
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label" for="contactusPhone">الهاتف</label>
    {{Form::text('contactusPhone',getSettingValue('contactusPhone'),['id'=>'contactusPhone','class'=>'form-control'])}}
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label" for="contactusAddress">العنوان</label>
    {{Form::text('contactusAddress',getSettingValue('contactusAddress'),['id'=>'contactusAddress','class'=>'form-control'])}}
  </div>

</div>
<!--/ form -->
