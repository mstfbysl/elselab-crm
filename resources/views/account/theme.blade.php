@php
$configData = Helper::applClasses();
@endphp

@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Theme Settings'))

@section('content')
<!-- Account Settings -->
<section>
    <div class="row">
        <div class="col-12">
            @include('account.partials._nav', ['active' => 'theme'])
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Theme Settings') }}</h4>
                </div>
                <div class="card-body mt-2">

                        <div class="row custom-options-checkable">
                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-color" type="radio" name="themeOption" id="themeOption1" value="light" data-layout=""/>
                                <label class="custom-option-item text-center p-1" for="themeOption1">
                                    <i data-feather="sun" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Light') }}</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-color" type="radio" name="themeOption" id="themeOption2" value="semi-dark" data-layout="semi-dark-layout"/>
                                <label class="custom-option-item text-center text-center p-1" for="themeOption2">
                                    <i data-feather="sunset" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Semi Dark') }}</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-color" type="radio" name="themeOption" id="themeOption3" value="dark" data-layout="dark-layout"/>
                                <label class="custom-option-item text-center text-center p-1" for="themeOption3">
                                    <i data-feather="moon" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Dark') }}</span>
                                </label>
                            </div>
                        </div>
                            <hr/>
                        <div class="row custom-options-checkable">
                            <div class="col-md-6">
                                <input class="custom-option-item-check theme-layout" type="radio" name="LayoutOption" id="layout-width-full" value="full"/>
                                <label class="custom-option-item text-center p-1" for="layout-width-full">
                                    <i data-feather="menu" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Full Width') }}</span>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <input class="custom-option-item-check theme-layout" type="radio" name="LayoutOption" id="layout-width-boxed" value="boxed"/>
                                <label class="custom-option-item text-center text-center p-1" for="layout-width-boxed">
                                    <i data-feather="package" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Boxed') }}</span>
                                </label>
                            </div>
                        </div>
                            <hr/>
                        <div class="row custom-options-checkable">
                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-nav-style" type="radio" name="StyleOption" id="nav-type-floating" value="floating"/>
                                <label class="custom-option-item text-center p-1" for="nav-type-floating">
                                    <i data-feather="layers" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Floating') }}</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-nav-style" type="radio" name="StyleOption" id="nav-type-sticky" value="sticky"/>
                                <label class="custom-option-item text-center text-center p-1" for="nav-type-sticky">
                                    <i data-feather="hexagon" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Sticky') }}</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check theme-nav-style" type="radio" name="StyleOption" id="nav-type-static" value="static"/>
                                <label class="custom-option-item text-center text-center p-1" for="nav-type-static">
                                    <i data-feather="square" class="font-large-1 mb-75"></i>
                                    <span class="custom-option-item-title h4 d-block">{{ __('locale.Static') }}</span>
                                </label>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Account Settings -->
@endsection

@section('page-script')
<script type="text/javascript">
$(function() {
    'use strict';

    AxiosGET('/api/account/detail', (r) => {

        let response = r.data;

        $('.theme-color[value="'+response.data.theme_color+'"]').prop('checked', true);
        $('.theme-layout[value="'+response.data.theme_layout+'"]').prop('checked', true);
        $('.theme-nav-style[value="'+response.data.theme_nav_style+'"]').prop('checked', true);

    })
    
    $('input').on("change", function(){

        let theme_color = $('.theme-color:checked').val();
        let theme_layout = $('.theme-layout:checked').val();
        let theme_nav_style = $('.theme-nav-style:checked').val();

        AxiosPOST('/api/account/save-theme', {theme_color, theme_layout, theme_nav_style}, (r) => {

            let response = r.data;

            if(response.status == true){
                location.reload();
            }

        })

    });

})

function save_theme(theme,style,layout){

}

</script>
@endsection