@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                </div>
            </div>
        </div>
    </div>
</div>
<script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="E4UPresenceBot" data-size="large" data-auth-url="callback/telegram" data-request-access="write"></script>
@endsection
