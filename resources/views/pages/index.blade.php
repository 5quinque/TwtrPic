@extends('app')

@section('content')

<div class="grid">
    <div class="search">
        <input id="searchbox" type="text" placeholder="Search"></input>
        <i class="fa fa-search fa-3x icon-search"></i>
    </div>
    <div class="settings"><i class="fa fa-cog fa-3x icon-setting"></i></div>
    <div class="grid-sizer"></div>
    <div class="loading">
        <i class="fa fa-3x fa-spinner fa-spin"></i>
    </div>
</div>

@stop

@include('footer')