<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $count_notifications = count($this->notifications());

        return view('back.index', compact('count_notifications'));
    }

    /**
     * Show notifications
     */
    public function showNotifications(){

        $notifications = $this->notifications();

        $notifications = count($notifications) > 0 ? $notifications : null;

        return view('back.notifications', compact('notifications'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        //
    }

    /**
     * Admin notifications
     * @return mixed
     */

    public function notifications(){

        $admin = get_auth();

        return $admin->unreadNotifications;
    }

    /**
     * Get all orders.
     *
     * @var Order $orders
     * @return view
     */

    public function getAllOrders()
    {

        $orders = Order::all();

        return view('back.admin', compact('orders'));
    }
}
