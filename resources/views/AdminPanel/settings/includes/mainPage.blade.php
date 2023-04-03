<div class="row">
    <div class="col-12 col-md-6">
        <label class="form-label" for="mainPageTitle_ar">العنوان بالصفحة الرئيسية بالعربية</label>
        {{Form::text('mainPageTitle_ar',getSettingValue('mainPageTitle_ar'),['id'=>'mainPageTitle_ar','class'=>'form-control'])}}
    </div>
    <div class="col-12  col-md-6">
        <label class="form-label" for="mainPageTitle_en">العنوان بالصفحة الرئيسية بالإنجليزية</label>
        {{Form::text('mainPageTitle_en',getSettingValue('mainPageTitle_en'),['id'=>'mainPageTitle_en','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        <label class="form-label" for="mainPageDes_ar">الوصف بالصفحة الرئيسية بالعربية</label>
        {{Form::textarea('mainPageDes_ar',getSettingValue('mainPageDes_ar'),['rows'=>'3','id'=>'mainPageDes_ar','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        <label class="form-label" for="mainPageDes_en">الوصف بالصفحة الرئيسية بالإنجليزية</label>
        {{Form::textarea('mainPageDes_en',getSettingValue('mainPageDes_en'),['rows'=>'3','id'=>'mainPageDes_en','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        {!! getSettingImageValue('mainPageImage1') !!}
        <label class="form-label" for="mainPageImage1"> #1 الصورة بالرئيسية</label>
        {{Form::file('mainPageImage1',['id'=>'mainPageImage1','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        {!! getSettingImageValue('mainPageImage2') !!}
        <label class="form-label" for="mainPageImage2"> #2 الصورة بالرئيسية</label>
        {{Form::file('mainPageImage2',['id'=>'mainPageImage2','class'=>'form-control'])}}
    </div>

</div>
