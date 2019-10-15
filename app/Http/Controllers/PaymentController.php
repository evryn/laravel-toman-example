<?php


namespace App\Http\Controllers;


use App\Payment;
use Evryn\LaravelToman\Facades\PaymentRequest;
use Evryn\LaravelToman\Facades\PaymentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required|string'
        ]);

        $requestedPayment = PaymentRequest::amount($request->amount)
            ->callback(URL::route('payment.callback'))
            ->description($request->description)
            ->request();

        Payment::create([
            'gateway' => 'zarinpal',
            'description' => $request->description,
            'amount' => $request->amount,
            'transaction_id' => $requestedPayment->getTransactionId(),
        ]);

        return $requestedPayment->pay();
    }

    public function callback(Request $request)
    {
        $payment = Payment::whereTransactionId($request->input('Authority'))->firstOrFail();

        $verifiedPayment = PaymentVerification::amount($payment->amount)->verify($request);

        $payment->update([
            'reference_id' => $verifiedPayment->getReferenceId()
        ]);
    }
}
