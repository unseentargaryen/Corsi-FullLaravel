<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\PendingBooking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function pay(Request $request)
    {

        $this->validate($request, [
            'lesson_id' => 'integer|required',
            'user_id' => 'integer',
            'amount' => 'numeric|required',
        ]);
        $amount = $request->amount;

        $user_id = Auth::guard()->user()->id ?? $request->user_id ?? null;
        if (!$user_id) {
            dd("fail");
        }
        $lesson_id = $request->lesson_id;
        $lesson = Lesson::find($lesson_id);
        if (!$lesson) {
            dd("fail");
        }

        Log::info($lesson->bookings()->count());
        Log::info($lesson->pendingBookings()->count());
        if (($lesson->bookings()->count() + $lesson->pendingBookings()->count()) >= $lesson->max_participants) {
           dd('-cancel');
        }

        $pendingBooking = new PendingBooking();
        $pendingBooking->user_id = $user_id;
        $pendingBooking->lesson_id = $lesson_id;
        $pendingBooking->amount = $amount;

        $pendingBooking->save();

        try {
            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => 'EUR',
                'returnUrl' => route('pending-success', ['id' => $pendingBooking->id]),
                'cancelUrl' => route('pending-cancel', ['id' => $pendingBooking->id]),
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                return $response->getMessage();
            }
        } catch (Exception $e) {
            return $e->getMessage();

        }
    }

    public function success(Request $request, $id)
    {
        if (!$id) {
            dd("fail");
        }
        $pendingBooking = PendingBooking::find($id);

        if (!$pendingBooking) {
            dd("fail");
        }

        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                $booking = new Booking();
                $booking->lesson_id = $pendingBooking->lesson_id;
                $booking->user_id = $pendingBooking->user_id;
                $booking->payment_id = $arr['id'];
                $booking->amount = $arr['transactions'][0]['amount']['total'];
                $booking->currency = $pendingBooking->currency;
                $booking->save();


                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = $pendingBooking->currency;
                $payment->payment_status = $arr['state'];
                $payment->booking_id = $booking->id;

                $payment->save();
                PendingBooking::destroy($pendingBooking->id);

                return ("Payment is Successfull. Your Transaction Id is : " . $arr['id']);

            } else {
                return $response->getMessage();
            }
        } else {
            return 'Payment declined!!';
        }
    }

    public function cancel($id)
    {
        $pendingBooking = PendingBooking::find($id);
        if ($pendingBooking) {
            PendingBooking::destroy($id);
        }

        return redirect()->route('home');
    }
}