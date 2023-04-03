<!-- form -->
<div class="row">
    <div class="col-md-3 text-center">
        {!! getSettingImageValue('logo') !!}
        <div class="file-loading">
            <input class="files" name="logo" type="file">
        </div>
    </div>

    {{-- <div class="divider">
        <div class="divider-text">البـانر</div>
    </div> --}}
    {{-- <div class="col-12 col-md-12">
        <label class="form-label" for="menuBannerURL">اللينك</label>
        {{Form::text('menuBannerURL',getSettingValue('menuBannerURL'),['id'=>'menuBannerURL','class'=>'form-control'])}}
    </div> --}}
    {{-- <div class="col-12 col-md-12">
        <label class="form-label" for="menuBannerImage">الصورة</label>
        {{Form::file('menuBannerImage',['id'=>'menuBannerImage','class'=>'form-control'])}}
    </div> --}}
</div>
<!--/ form -->
