@extends('app')

@section('content')

<div class="grid">
    <div class="search">
        <input id="searchbox" type="text" placeholder="Search"></input>
        <i class="fa fa-search fa-3x icon-search"></i>
    </div>
    <div class="settings">
        <i class="fa fa-cog fa-3x icon-setting"></i>
        <div class="switch">
            <input name="autoupdate" type="radio" value="0" id="au_on" checked>
            <input name="autoupdate" type="radio" value="1" id="au_off">
            <label class='label1' for="au_on">Auto Update</label>
            <label class='label2' for="au_off">Pause</label>
        </div>
    </div>
    <div class="grid-sizer"></div>
    <div class="loading">
        <i class="fa fa-3x fa-spinner fa-spin"></i>
    </div>
</div>

@stop

@include('footer')