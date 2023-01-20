@extends('panel.layout.master')
@section('title',$currency->name.'('.$currency->label .')'.' Detail')

@section('content')

@include('panel.modules.currency.detail')

@endsection
