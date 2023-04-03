<div class="row">
    <div class="col-12 col-md-6">
        <label class="form-label" for="featureTitle_ar">العنوان بالعربية</label>
        {{Form::text('featureTitle_ar',getSettingValue('featureTitle_ar'),['id'=>'featureTitle_ar','class'=>'form-control'])}}
    </div>
    <div class="col-12  col-md-6">
        <label class="form-label" for="featureTitle_en">العنوان بالإنجليزية</label>
        {{Form::text('featureTitle_en',getSettingValue('featureTitle_en'),['id'=>'featureTitle_en','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        <label class="form-label" for="featureDes_ar">الوصف بالعربية</label>
        {{Form::textarea('featureDes_ar',getSettingValue('featureDes_ar'),['rows'=>'3','id'=>'featureDes_ar','class'=>'form-control'])}}
    </div>
    <div class="col-12">
        <label class="form-label" for="featureDes_en">الوصف بالإنجليزية</label>
        {{Form::textarea('featureDes_en',getSettingValue('featureDes_en'),['rows'=>'3','id'=>'featureDes_en','class'=>'form-control'])}}
    </div>
    <div class="col-12 col-md-12">
      <label class="form-label" for="featureImage">الصورة</label>
      {{Form::file('featureImage',['id'=>'featureImage','class'=>'form-control'])}}
    </div>
    <div class="divider">
        <div class="divider-text"><b>التفاصـيل</b></div>
    </div>
    @for($i=1;$i<=4;$i++)
        <div class="row pt-2 pb-4">
            <h3>الأيقونة #{{$i}}</h3>
            <div class="col-md-4 text-center">
                {!! getSettingImageValue('feature'.$i.'icon') !!}
                <div class="file-loading">
                    <input class="files" name="feature{{$i}}icon" type="file">
                </div>
            </div>
            <div class="col-md-12"></div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="feature{{$i}}title_ar">#{{ $i }} العنوان بالعربية</label>
                {{Form::text('feature_'.$i.'title_ar',getSettingValue('feature_'.$i.'title_ar'),['id'=>'feature'.$i.'title_ar','class'=>'form-control'])}}
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="feature{{$i}}title_en">#{{ $i }} العنوان بالإنجليزية</label>
                {{Form::text('feature_'.$i.'title_en',getSettingValue('feature_'.$i.'title_en'),['id'=>'feature'.$i.'title_en','class'=>'form-control'])}}
            </div>

            <div class="col-md-12"></div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="feature{{$i}}des_ar">#{{ $i }} الوصف بالعربية</label>
                {{Form::textarea('feature_'.$i.'des_ar',getSettingValue('feature_'.$i.'des_ar'),['id'=>'feature'.$i.'des_ar','class'=>'form-control','rows'=>'3'])}}
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="feature{{$i}}des_en">#{{ $i }} الوصف بالإنجليزية</label>
                {{Form::textarea('feature_'.$i.'des_en',getSettingValue('feature_'.$i.'des_en'),['id'=>'feature'.$i.'des_en','class'=>'form-control','rows'=>'3'])}}
            </div>
        </div>
    @endfor
</div>
