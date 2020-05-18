<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class PaymentController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_S_KEY'));
    }

    public function index()
    {
        $data = session('data');
        return view('post.payment',['title'=>$data['title']]);
    }

    public function payment(Request $request)
    {
        var_dump($request->input());
        
        \Stripe\PaymentIntent::create([
            'amount' => 99,
            'currency' => 'inr',
            'payment_method_types' => ['card'],
            'metadata' => [
                'order_id' => 1,
            ],
        ]);
        //return view('post.payment',['title'=>$data->title]);
    }
}
