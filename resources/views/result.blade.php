<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Payment Result</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
            <div class="card">
                <div class="card-header">
                    Payment Result
                </div>
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-warning">
                            {{ $error }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            Payment was successful. Reference ID: {{$payment->reference_id}}
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{URL::route('payment.new')}}">New Payment</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
