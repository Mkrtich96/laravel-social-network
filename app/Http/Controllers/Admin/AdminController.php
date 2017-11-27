<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AllowProduct;
use App\Notifications\ToMail;
use App\Order;
use App\User;
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

        $orders = Order::all();

        $orders = $orders->count() ? $orders : null;

        return view('back.index', compact('count_notifications', 'orders'));
    }

    /**
     * Show notifications
     */
    public function showNotifications(){

        $notifications = $this->notifications();
        $notifications = $notifications->count() ? $notifications : null;

        return view('back.notifications', compact('notifications'));
    }

    public function approveProduct(AllowProduct $request){

        $admin = get_auth();
        $user = User::find($request->user);

        $approve = $user->products()
                        ->where('id', $request->product)
                        ->update(['status' => true]);

        if($approve){

            $notification = $admin->notifications()
                                    ->where('to', $request->user)
                                    ->first();

            if($notification){
                $delete = $notification->delete();

                if($delete){

                    $user->notify(new ToMail(true));

                    return redirect()
                        ->back()
                        ->with('success', 'The user product has been allowed.');
                }
            }
        }

        return redirect()
                    ->back()
                    ->with('error', 'The user product dont saved as allowed!');

    }

    public function deniedProduct(AllowProduct $request){

        $admin = get_auth();
        $user = User::find($request->user);
        $user->notify(new ToMail(false));

        $notification = $admin->notifications()
                                ->where('to', $request->user)
                                ->first();

        if($notification->delete()){

            return redirect()
                        ->back()
                        ->with('success', 'User product denied successfully');
        }

        return redirect()->back()->with('error', 'Notification don\'t deleted');
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

}
