<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>
        پرداخت جدید
    </title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="direction: rtl; text-align: right">
<div class="container mt-5">
    <div class="row justify-content-md-center">
        <div class="col-md-auto">
            <div class="card">
                <div class="card-header">
                    پرداخت جدید
                </div>
                <div class="card-body">
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <div>{{ $error }}</div>
                            </div>
                        @endforeach
                    @endif

                    <form method="POST" action="{{ route('payment.store') }}">
                        @csrf
                        <div>
                            <label>مبلغ:</label>
                            <input name="amount" value="{{ old('amount', 2000) }}" class="form-control">
                        </div>
                        <div>
                            <label>توضیحات:</label>
                            <input name="description" value="{{ old('description', '...') }}" class="form-control">
                        </div>
                        <div class="pt-4">
                            <input type="submit" class="form-control btn-info" value="پرداخت">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
