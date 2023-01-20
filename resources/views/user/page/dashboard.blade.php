@extends('site.layout.master')

@section('main_content')
<div class="container">
    <h3 class="mt-5">Welcome {{ Auth::guard('member')->user()->name }}</h3>
    <div class="row mt-5">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Active Listings</h5>
                  <p class="card-text"><h3>{{ $active_listing }}</h3></p>
                  <a href="{{ route('member.listings') }}" class="card-link">Show Listings</a>
                </div>
              </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Closed Listings</h5>
                  <p class="card-text"><h3>{{ $passive_listing }}</h3></p>
                  <a href="{{ route('member.listings') }}" class="card-link">Show Listings</a>
                </div>
              </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Messages</h5>
                  <p class="card-text"><h3>{{ $messages }}</h3></p>
                  <a href="{{ route('member.messages') }}" class="card-link">Show Messages</a>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection
