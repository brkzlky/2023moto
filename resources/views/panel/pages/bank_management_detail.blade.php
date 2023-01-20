@extends('panel.layout.master')
@section('title',$bank->name.' Detail')

@section('content')

@include('panel.modules.bank_management.detail')
@endsection
