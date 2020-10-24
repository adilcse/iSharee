<?php

/**
 * Handles payment for article publication
 *
 * PHP version : 7.0
 *
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/CommentController.php
 */

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Model\Article;
use App\Model\Payment;
use App\Events\NewArticleAdded;
use Illuminate\Support\Facades\Log;

/**
 * Control payment for articles
 *
 * @category Controller/Article
 * @package  Http/Controller/Article
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Article/CommentController.php
 */
class PaymentController extends Controller
{
    /**
     * Set stripe api key from env
     *
     * @return void
     */
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('payment.stripe.secret'));
    }

    /**
     * Sent payment page
     *
     * @return view payment page
     */
    public function index()
    {
        $data = session('data');
        return view('post.payment', ['data' => $data]);
    }

    /**
     * Create a new payment and stroredetails in database
     *
     * @param Request $request http request
     *
     * @return view with payment confirmation
     */
    public function payment(Request $request)
    {
        Log::debug($request);
        try {
            $pay = \Stripe\Charge::create(
                [
                    'amount' => 99 * 100,
                    'currency' => 'inr',
                    'source' => $request->stripeToken,
                    'receipt_email' => Auth::user()->email,
                    ['metadata' => ['order_id' => $request->orderId]]
                ]
            );
            $payment = new Payment();
            $payment->user_id = Auth::id();
            $payment->article_id = $request->orderId;
            $payment->txn_id = $pay->balance_transaction;
            $payment->payment_id = $pay->id;
            $payment->status = $pay->paid;
            $payment->payment_method = $pay->payment_method;
            $payment->receipt_url = $pay->receipt_url;
            $payment->amount = $pay->amount / 100;
            $payment->save();
            if ($pay->paid) {
                $article = Article::find($request->orderId);
                if (0 !== Auth::id()) {
                    $article->is_published = 1;
                }
                $article->paid = 1;
                $article->save();
                //create an event that a new article is added to notify admin
                //    event(new NewArticleAdded($article));
                return view(
                    'post.paymentStatus',
                    ['status' => 'success', 'article' => $article, 'payment' => $payment]
                );
            }
            return view(
                'post.paymentStatus',
                ['status' => 'failed', 'article' => $article, 'payment' => $payment]
            );
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['payment-failed' => 'message']);
        }
    }
}
