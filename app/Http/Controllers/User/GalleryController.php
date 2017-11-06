<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\GalleriesDestroy;
use App\Http\Requests\GalleryProfilePhoto;
use App\Http\Requests\StoreGalleries;
use File;
use App\User;
use App\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleries $request)
    {

        $data = array();
        $user_id = get_auth('id');
        $files = $request->file('gallery');

        foreach ($files as $file) {
            $name = $file->store('public/' . $user_id . '/gallery');
            $data[] = [
                'user_id'   => $user_id,
                'image'     => basename($name)
            ];
        }
        $user_galleries = Gallery::insert($data);

        if ($user_galleries) {
            return redirect()->back()->with('success', 'Gallery updated!');
        } else {
            return redirect()->back()->with('fail', 'Connection error');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = array();
        $user = User::find($id);
        $galleries = $user->galleries;

        if (count($galleries) > 0) {
            foreach ($galleries as $image) {
                $gallery[] = [
                    'id' => $image->id,
                    'image' => $image->image,
                    'user_id' => $id,
                ];
            }
        } else {
            $gallery = null;
        }

        return view('front.profile.gallery', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    public function makeProfilePhoto(GalleryProfilePhoto $request)
    {

        $user_id = get_auth('id');
        $user   = User::find($user_id);

        $gallery  = $user->galleries()->find($request->image_id);

        unlink(storage_path('app/public/' . $user_id . "/" . $user->avatar));
        $copy   = storage_path('app/public/' . $user_id . "/gallery/" . $gallery->image);
        $to     = storage_path('app/public/' . $user_id . "/" . $gallery->image);

        File::copy($copy, $to);
        $user->avatar = $gallery->image;
        $user_profile_photo_change = $user->save();

        if ($user_profile_photo_change) {
            return redirect('/')->with('status_202', 'Profile photo updated!');
        } else {
            return redirect()->back()->with('status_404', 'Connection error!');
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleriesDestroy $request, $id)
    {

        $src = basename($request->src);
        $user_id = get_auth('id');
        unlink(storage_path('app/public/' . $user_id . '/gallery/' . $src));

        $user = User::find($user_id);

        $delete = $user->galleries()->find($id)->delete();

        if($delete){
            return response([
                'status' => 'success'
            ], 200);
        }

        return response([
            'status' => 'fail',
            'message'=> 'Gallery delete response not found. Error 404.'
        ], 404);
    }
}
