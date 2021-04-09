<?php

namespace Tests\Feature;

use App\Models\Payment;
use Evryn\LaravelToman\Facades\Toman;
use Evryn\LaravelToman\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_request_new_payment()
    {
        // Arrangement
        Toman::fakeRequest()->successful()->withTransactionId('A1234');

        // Acting
        $this->post('/payment', [
            'amount' => 5000,
            'description' => 'My payment description.'
        ])->assertSessionHasNoErrors()->assertRedirect();

        // Assertions
        Toman::assertRequested(function ($request) {
            return $request->callback() === route('payment.callback')
                &&  $request->description() === 'My payment description.'
                && $request->amount()->is(Money::Toman(5000));
        });

        self::assertEquals(1, Payment::count());

        tap(Payment::first(), function (Payment $payment) {
            self::assertEquals('A1234', $payment->transaction_id);
        });
    }

    /** @test */
    public function can_fail_with_a_payment_request()
    {
        // Arrangement
        Toman::fakeRequest()->failed();

        // Acting
        $this->post('/payment', [
            'amount' => 5000,
            'description' => 'My payment description.'
        ])->assertSessionHasErrors();

        // Assertions
        Toman::assertRequested(function ($request) {
            return $request->callback() === route('payment.callback')
                &&  $request->description() === 'My payment description.'
                && $request->amount()->is(Money::Toman(5000));
        });

        self::assertEquals(0, Payment::count());
    }

    /** @test */
    public function can_verify_successful_incoming_callback()
    {
        // Arrangement
        Toman::fakeVerification()
            ->successful()
            ->withTransactionId('A1234')
            ->withReferenceId('R7000');

        Payment::factory()->create([
            'amount' => 5000,
            'transaction_id' => 'A1234'
        ]);

        $this->withoutExceptionHandling();
        // Acting
        $this->get('/payment/callback')->assertOk()
            ->assertSee('R7000')
            ->assertSee('موفقیت')
            ->assertDontSee('شکست')
            ->assertDontSee('قبلاً');

        // Assertions
        Toman::assertCheckedForVerification(function ($request) {
            return $request->transactionId() === 'A1234'
                && $request->amount()->is(Money::Toman(5000));
        });

        tap(Payment::first(), function (Payment $payment) {
            self::assertEquals('R7000', $payment->reference_id);
            self::assertNotNull($payment->paid_at);
            self::assertNull($payment->failed_at);
        });
    }

    /** @test */
    public function handles_already_verified_incoming_callback()
    {
        // Arrangement
        Toman::fakeVerification()
            ->alreadyVerified()
            ->withTransactionId('A1234')
            ->withReferenceId('R7000');

        Payment::factory()->create([
            'amount' => 5000,
            'transaction_id' => 'A1234',
            'reference_id' => 'R7000',
            'paid_at' => '2021-01-01 00:00:00'
        ]);

        // Acting
        $this->get('/payment/callback')->assertOk()
            ->assertSee('R7000')
            ->assertSee('قبلاً')
            ->assertDontSee('موفقیت')
            ->assertDontSee('شکست');

        // Assertions
        Toman::assertCheckedForVerification(function ($request) {
            return $request->transactionId() === 'A1234'
                && $request->amount()->is(Money::Toman(5000));
        });

        tap(Payment::first(), function (Payment $payment) {
            self::assertEquals('R7000', $payment->reference_id);
            self::assertEquals('2021-01-01 00:00:00', $payment->paid_at);
            self::assertNull($payment->failed_at);
        });
    }

    /** @test */
    public function handles_failed_incoming_callback()
    {
        // Arrangement
        Toman::fakeVerification()
            ->failed()
            ->withTransactionId('A1234');

        Payment::factory()->create([
            'amount' => 5000,
            'transaction_id' => 'A1234'
        ]);

        // Acting
        $this->get('/payment/callback')->assertOk()
            ->assertSee('شکست')
            ->assertDontSee('موفقیت')
            ->assertDontSee('قبلاً');

        // Assertions
        Toman::assertCheckedForVerification(function ($request) {
            return $request->transactionId() === 'A1234'
                && $request->amount()->is(Money::Toman(5000));
        });

        tap(Payment::first(), function (Payment $payment) {
            self::assertNotNull($payment->failed_at);
            self::assertNull($payment->reference_id);
            self::assertNull($payment->paid_at);
        });
    }
}
