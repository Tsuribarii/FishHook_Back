<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class ImageController extends Controller
{
    public function image()
    {
        $img = DB::table('images')
            ->select('filename', 'url')
            ->leftJoin('users', 'images.user_id', '=', 'users.id')
            ->get();
        return response()->json(
            $img
        );
    }
    public function store(Request $request){
        $this->validate($request, ['image' => 'required|image']);
        if($request->hasfile('image'))
         {
            $file = $request->file('image');
            $name= time().$file->getClientOriginalName();
            $filePath = 'image/' . $name;
            $url = 'https://awsfishhook.s3.ap-northeast-2.amazonaws.com/' . $filePath;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
         }
         $user = JWTAuth::parseToken()->authenticate();
         Image::create([
            'user_id'   => $user->id,
            'filename'   => $name,
            'url' => $url
         ]);
    }
}
