<?php

namespace App\Http\Controllers\User;

use App\Product;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Error\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    /**
     * Make a Stripe payment.
     * @param Product $product
     * @return method chargeCustomer()
     */

    public function postPayWithStripe(Request $request, Product $product)
    {

        $auth = get_auth();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        if (!$this->isStripeCustomer()) {

            $customer = $this->createStripeCustomer($request->stripeToken);
        } else {

            $customer = Customer::retrieve($auth->stripe_id);
        }

        if($customer){

            return $this->createStripeCharge($product->id, $product->price, $product->name, $customer);
        }

        return redirect()
                ->back()
                ->with('error', 'Connection error, please try again later.');
    }

    /**
     * Create a Stripe charge.
     *
     * @var Charge $charge
     * @var Card $e
     * @param integer $product_id
     * @param integer $product_price
     * @param string $product_name
     * @param Customer $customer
     * @return postStoreOrder()
     */

    public function createStripeCharge($product_id, $product_price, $product_name, $customer){

        try {
            Charge::create([
                "amount" => $product_price,
                "currency" => "usd",
                "customer" => $customer->id,
                "description" => $product_name
            ]);
        } catch(Card $e) {

            return redirect()
                ->back()
                ->with('error', 'Your credit card was been declined. Please try again or contact us.');
        }

        return $this->postStoreOrder($product_name, $product_id);
    }


    /**
     * Create a new Stripe customer for a given user.
     *
     * @var Customer $customer
     * @param string $token
     * @return Customer $customer
     */
    public function createStripeCustomer($token)
    {
        $auth = get_auth();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            "description" => $auth->email,
            "source" => $token
        ]);
        if($customer){

            $auth->stripe_id = $customer->id;
            $save_stripe_id = $auth->save();

            if($save_stripe_id){

                return $customer;
            }
        }
        return redirect()
                ->back()
                ->with('error', 'Connection error, please try again later.');
    }

    /**
     * Check if the Stripe customer exists.
     *
     * @return boolean
     */
    public function isStripeCustomer()
    {
        $auth = get_auth();

        return $auth && $auth->whereNotNull('stripe_id')->first();
    }

    /**
     * Store a order.
     * @param string $product_name
     * @return redirect()
     */

    public function postStoreOrder($product_name, $product_id)
    {
        $auth = get_auth();

            $auth->orders()->create([
                'email' => $auth->email,
                'product' => $product_name,
                'product_id' => $product_id
            ]);

        return redirect()
            ->back()
            ->with('msg', 'Thanks for your purchase!');
    }

}
