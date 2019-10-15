<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>New Payment</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <div class="card">
                        <div class="card-header">
                            New Payment
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        <div>{{ $error }}</div>
                                    </div>
                                @endforeach
                            @endif

                            {!! Form::open(['route' => 'payment.create']) !!}
                                <div>{!! Form::text('amount', '2000', ['class' => 'form-control']) !!}</div>
                                <div class="mt-3">{!! Form::textarea('description', 'My payment ...', ['class' => 'form-control']) !!}</div>
                                <div class="mt-3">{!! Form::submit('Pay', ['class' => 'form-control btn-info']) !!}</div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
