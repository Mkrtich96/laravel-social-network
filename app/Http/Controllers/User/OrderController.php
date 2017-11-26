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

        $token = $request->stripeToken;

        if (!$this->isStripeCustomer()) {

            $customer = $this->createStripeCustomer($token);
        } else {

            $customer = Customer::retrieve($auth->stripe_id);
        }

        if($customer){

            return $this->createStripeCharge($product, $token);
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

    public function createStripeCharge($product, $token){

        if($this->postStoreOrder($product)){
            try {

                Charge::create([
                    "amount" => $product->price,
                    "currency" => "usd",
                    "source" => $token,
                    "destination" => [
                        "account" => $product->user->stripe_account_id,
                    ],
                ]);

                $delete_product = $product->delete();

                if($delete_product){

                    return redirect()
                        ->back()
                        ->with('msg', 'Thanks for your purchase!');
                }

                return redirect()
                        ->back()
                        ->with('error', 'Product purchased, but not deleted.');

            } catch(Card $e) {

                return redirect()
                    ->back()
                    ->with('error', 'Your credit card was been declined. Please try again or contact us.');
            }
        }

        return redirect()
                ->back()
                ->with('error', 'Please try again later, errors with connection');
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

    public function postStoreOrder($product)
    {
        $auth = get_auth();
        $order_ok = true;

        $create_order = $auth->orders()->create([
                            'email' => $auth->email,
                            'product' => $product->name,
                            'product_id' => $product->id
                        ]);

        if(is_null($create_order)){

            $order_ok = false;
        }

        return $order_ok;
    }

}
