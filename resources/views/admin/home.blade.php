@extends('layouts.admin')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                        <a style="text-align: center" href="{{ route('admin.dishes.index') }}">Tutti i piatti</a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col">

                {!! $chart->container() !!}

                {!! $chart->script() !!}
            </div>
        </div> --}}
    </div>
@endsection
