<!-- form -->
<div class="row">
    <div class="divider">
        <div class="divider-text">{{trans('common.siteMainSEO')}}</div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="siteTitle_ar">{{trans('common.siteTitle_ar')}}</label>
        {{Form::text('siteTitle_ar',getSettingValue('siteTitle_ar'),['id'=>'siteTitle_ar','class'=>'form-control'])}}
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="siteTitle_en">{{trans('common.siteTitle_en')}}</label>
        {{Form::text('siteTitle_en',getSettingValue('siteTitle_en'),['id'=>'siteTitle_en','class'=>'form-control'])}}
    </div>
    <div class="col-12 col-md-12">
        <label class="form-label" for="siteDescription">{{trans('common.siteDescription')}}</label>
        {{Form::textarea('siteDescription',getSettingValue('siteDescription'),['rows'=>'3','id'=>'siteDescription','class'=>'form-control'])}}
    </div>

</div>
<!--/ form -->
