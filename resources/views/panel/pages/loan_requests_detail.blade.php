@extends('panel.layout.master')
@section('title',$loan_request->fullname. "'s Request Detail")

@section('content')

@include('panel.modules.loan_requests.detail')


@endsection