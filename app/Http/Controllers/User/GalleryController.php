<?php

namespace App\Http\Controllers\User;

use Auth;
use File;
use App\User;
use App\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data   = [];
        $user_id = get_auth_id();
        $images = Gallery::where('user_id',$user_id)->get();
        if(count($images) > 0){
            foreach ($images as $image) {
                $data[] = [
                    'id'        => $image->id,
                    'image'     => $image->image,
                    'user_id'   => $user_id,
                ];
            }
        }

        return view('front.profile.gallery', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = get_auth_id();

        $data = [];
        if ($request->hasFile('gallery')) {
            $files = $request->file('gallery');
            foreach ($files as $file) {
                $name = $file->store('public/' . $user_id . '/gallery');
                $data[] = ['user_id' => $user_id, 'image' => basename($name)];
            }
            if (Gallery::insert($data)) {
                return redirect()->back()->with('status_200', 'Gallery updated!');
            } else {
                return redirect()->back()->with('status_404', 'Connection error');
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user_id    = get_auth_id();

        $image      = Gallery::find($id);

        $user       = User::find($user_id);

        unlink(storage_path('app/public/' . $user_id . "/" . $user->avatar));

        $file       = storage_path('app/public/' . $user_id . "/gallery/" . $image->image);

        $to         = storage_path('app/public/' . $user_id . "/" . $image->image);

        File::copy($file, $to);

        $user->avatar = $image->image;

        if($user->save()){
            return redirect('/')->with('status_202', 'Profile photo updated!');
        }else{
            return redirect()->back()->with('status_404','Connection error!');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if($request->ajax()){
            $rules = [
                'src' => 'regex:/^([a-zA-Z1-9]+)\.([a-z]{3,5})$/',
            ];

            $validate = $this->validate($request, $rules);

            if(!is_null($validate)){
                return response(null, 404);
            }

            $src = basename($request->src);
            $user_id = \Auth::user()->id;
            unlink(storage_path('app/public/'. $user_id . '/gallery/' . $src));
            $image = Gallery::find($id);
            if($image->delete()){
                return response(['ok' => 1],200);
            }else{
                return response(null, 404);
            }
        }
    }
}
