@if ($errors->any())
    @foreach ($errors->all() as $k => $e)

        <div class="alert {{ $k === 'success' ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show" role="alert">
            {!! $e !!}
        </div>

    @endforeach
@endif

@if(Session::has('error'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
      {!! Session::get('error') !!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
      </button>
  </div>
@endif

@if(Session::has('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {!! Session::get('success') !!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
      </button>
  </div>
@endif
