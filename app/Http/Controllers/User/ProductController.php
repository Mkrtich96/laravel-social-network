<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductStore;
use App\Notifications\RepliedToPublicProduct;

class ProductController extends Controller
{

    protected $connect_authorize_url = "https://connect.stripe.com/oauth/authorize?";
    protected $connect_response_type = 'code';
    protected $connect_scope = 'read_write';

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $auth = get_auth();


        if( is_null($auth->stripe_account_id) ){
            $this->connect_authorize_url .= "response_type=" . $this->connect_response_type;
            $this->connect_authorize_url .= "&client_id=" . env('STRIPE_CLIENT_ID');
            $this->connect_authorize_url .= "&scope=" . $this->connect_scope;

            $connect_url = $this->connect_authorize_url;
        }else{
            $products = $auth->products;
        }


        return view('front.profile.products', compact('auth','products', 'connect_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductStore $request)
    {
        $auth = get_auth();

        $cut_price = substr($request->price, strlen($request->price) - 2);
        $price = (int)$request->price . $cut_price;

        $save_product = $auth->products()->create([
                            'name' => $request->name,
                            'description' => $request->description,
                            'price' => $price,
                            'status' => false
                        ]);

        if($save_product){

            $admin = User::where('admin', true)->first();
            $admin->notify(new RepliedToPublicProduct($save_product, $auth));

            return redirect()
                ->back()
                ->with('success', 'Product created successfully, please wait allow administrators, for public.');
        }

        return redirect()
                    ->back()
                    ->with('error', 'Product don\' created. Please try again later.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
