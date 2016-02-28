@extends('app')

@section('content')

<div class="grid">
    <div class="search">
        <input id="searchbox" type="text" placeholder="Search"></input>
        <i class="fa fa-search fa-3x icon-search"></i>
    </div>
    <div class="settings"><i class="fa fa-cog fa-3x icon-setting"></i>
        <div class="switch">
            <input name="radio" type="radio" value="optionone" id="optionone" checked>
            <label for="optionone">SFW</label>

            <input name="radio" type="radio" value="optiontwo" id="optiontwo">
            <label for="optiontwo" class="right">NSFW</label>

            <span aria-hidden="true"></span>
        </div>
    </div>
    <div class="grid-sizer"></div>
    <div class="loading">
        <i class="fa fa-3x fa-spinner fa-spin"></i>
    </div>
</div>

@stop

@include('footer')