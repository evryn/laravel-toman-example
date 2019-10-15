<?php

namespace Tests\Feature;

use App\Payment;
use Evryn\LaravelToman\Facades\PaymentRequest;
use Evryn\LaravelToman\Facades\PaymentVerification;
use Evryn\LaravelToman\Results\RequestedPayment;
use Evryn\LaravelToman\Results\VerifiedPayment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function requests_with_correct_details()
    {
        $this->withoutExceptionHandling();
        static::assertEquals(0, Payment::count());

        PaymentRequest::shouldReceive('callback')->once()->with(URL::route('payment.callback'))->andReturnSelf();
        PaymentRequest::shouldReceive('description')->once()->with('My donation')->andReturnSelf();
        PaymentRequest::shouldReceive('amount')->once()->with(1500)->andReturnSelf();
        PaymentRequest::shouldReceive('request')->once()->withNoArgs()->andReturn(
            new RequestedPayment('000123', 'https://example.com/000123/pay')
        );

        $this->post('/new-payment', [
            'amount' => 1500,
            'description' => 'My donation'
        ])->assertRedirect('https://example.com/000123/pay');

        $payment = Payment::first();

        static::assertEquals(1500, $payment->amount);
        static::assertEquals('My donation', $payment->description);
        static::assertEquals('000123', $payment->transaction_id);
    }

    /** @test */
    public function verifies_payment_after_being_redirected_to_callback_page()
    {
        $payment = factory(Payment::class)->create([
            'amount' => 2000,
            'transaction_id' => '1111',
        ]);

        PaymentVerification::shouldReceive('amount')->once()->with(2000)->andReturnSelf();
        PaymentVerification::shouldReceive('verify')->once()->with(\Mockery::type(Request::class))->andReturn(
            new VerifiedPayment('123456789')
        );

        // 'Authority' is one of Zarinpal's callback data
        $this->get('/payment-callback?Authority=1111')->assertOk();

        self::assertEquals('123456789', $payment->fresh()->reference_id);
    }

    /**
     * There are other valuable tests to write here including:
     * - verification must happen on correct payment record
     * - new payment amount is required integer
     * - new payment amount can't be lower than 100
     * - new payment description is required string
     * - correct data are being sent from PaymentController@new form
     * - correct payment record info is being shown after verification
     * - etc.
     */

}
