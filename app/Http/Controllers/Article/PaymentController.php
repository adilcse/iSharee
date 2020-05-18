<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Model\Article;
use App\Model\Payment;
use App\Events\NewArticleAdded;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_S_KEY'));
    }

    public function index()
    {
        $data = session('data');
        return view('post.payment',['data'=>$data]);
    }

    public function payment(Request $request)
    {
        try{
            $pay=\Stripe\Charge::create([
                'amount' => 99 * 100,
                'currency' => 'inr',
                'source'=>$request->stripeToken,
                'receipt_email'=>Auth::user()->email,
                ['metadata' => ['order_id' => $request->orderId]]
            ]);
            $payment = new Payment();
            $payment->user_id = Auth::id();
            $payment->article_id = $request->orderId;
            $payment->txn_id = $pay->balance_transaction;
            $payment->payment_id = $pay->id;
            $payment->status = $pay->paid;
            $payment->payment_method = $pay->payment_method;
            $payment->receipt_url = $pay->receipt_url;
            $payment->amount=$pay->amount/100;
            $payment->save();
            if($pay->paid){
                $article = Article::find($request->orderId);
                $article->is_published = 1;
                $article->paid = 1;
                $article->save();
                  //create an event that a new article is added to notify admin
            event(new NewArticleAdded($article)); 
                return view('post.paymentStatus',['status'=>'success','article'=>$article,'payment'=>$payment]);
            }
            return view('post.paymentStatus',['status'=>'failed','article'=>$article,'payment'=>$payment]);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['payment-failed'=>'message']);
        }
    }
}
