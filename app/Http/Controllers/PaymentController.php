<?php


namespace App\Http\Controllers;


use App\Payment;
use Evryn\LaravelToman\Exceptions\GatewayException;
use Evryn\LaravelToman\Facades\PaymentRequest;
use Evryn\LaravelToman\Facades\PaymentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function new()
    {
        return view('new');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'amount' => 'bail|required|integer|gt:0',
            'description' => 'bail|required|string'
        ]);

        try {
            $requestedPayment = PaymentRequest::amount($request->amount)
                ->callback(URL::route('payment.callback'))
                ->description($request->description)
                ->request();
        } catch (GatewayException $exception) {
            return back()->withErrors($exception->getMessage());
        }

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

        try {
            $verifiedPayment = PaymentVerification::amount($payment->amount)->verify($request);
        } catch (GatewayException $exception) {
            return view('result')->with('payment', $payment)->with('error', $exception->getMessage());
        }

        $payment->update([
            'reference_id' => $verifiedPayment->getReferenceId()
        ]);
        return view('result')->with('payment', $payment);
    }
}
