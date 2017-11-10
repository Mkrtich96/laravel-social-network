<?php

namespace App\Http\Controllers\User;

use File;
use App\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleries;
use App\Http\Requests\GalleriesDestroy;
use App\Http\Requests\GalleryProfilePhoto;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = get_auth();
        $galleries = $user->galleries;
        $gallery = (count($galleries)) > 0 ? $galleries : null;

        return view('front.profile.gallery', compact('gallery'));
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
        $user = get_auth();
        $files = $request->file('gallery');

        foreach ($files as $file) {
            $name = $file->store('public/' . $user->id . '/gallery');
            $data[] = new Gallery(['image'  => basename($name)]);
        }

        $user_galleries = $user->galleries()->saveMany($data);

        if ($user_galleries) {
            return redirect()->back()->with('success', 'Gallery updated!');
        } else {
            return redirect()->back()->with('fail', 'Connection error.');
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

        $user = get_auth();

        $gallery = $user->galleries()->find($request->image_id);

        unlink(storage_path('app/public/' . $user->id . "/" . $user->avatar));
        $copy = storage_path('app/public/' . $user->id . "/gallery/" . $gallery->image);
        $to = storage_path('app/public/' . $user->id . "/" . $gallery->image);

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
        $user = get_auth();
        unlink(storage_path('app/public/' . $user->id . '/gallery/' . $src));
        $galleries = $user->galleries()->find($id);

        if($galleries){

            if($galleries->delete()){
                return response([
                    'status' => 'success',
                    'message'=> 'Gallery delete successfully complete.',
                ], 200);
            }
        }

        return response([
            'status' => 'fail',
            'message'=> 'Gallery delete response not found. Error 404.'
        ], 404);
    }
}
