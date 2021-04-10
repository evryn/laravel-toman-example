<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>نتیجه پرداخت</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="direction: rtl; text-align: right">
<div class="container mt-5">
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
            <div class="card">
                <div class="card-header">
                    نتیجه پرداخت
                </div>
                <div class="card-body">
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <div>{{ $error }}</div>
                            </div>
                        @endforeach
                    @endif

                    @if($verification->successful())
                        <div class="alert alert-success">
                            پرداخت موفقیت آمیز بود. شناسه ارجاع:
                            {{ $payment->reference_id }}
                        </div>
                    @elseif($verification->alreadyVerified())
                        <div class="alert alert-warning">
                            انجام این پرداخت قبلاً تایید شده است. شناسه ارجاع:
                            {{ $payment->reference_id }}
                        </div>
                    @else
                        <div class="alert alert-danger">
                            پرداخت شکست خورد.
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('payment.create') }}">پرداخت جدید</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
