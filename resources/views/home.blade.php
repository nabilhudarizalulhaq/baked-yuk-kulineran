@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg col">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{ __('You are logged in!') }}
    </div>
  </div>
  <!-- /.row (main row) -->
@endsection
