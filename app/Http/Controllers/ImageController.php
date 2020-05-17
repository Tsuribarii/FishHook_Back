<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\DB;
use Storage;
// use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\Filesystem;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

$client = new S3Client([
    'credentials' => [
        'key'    => 'AKIA5XMMML6ETA5M63NP',
        'secret' => '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'
    ],
    'region' => 'ap-northeast-2',
]);

$adapter = new AwsS3Adapter($client, 'awsfishhook');
$filesystem = new Filesystem($adapter);
class ImageController extends Controller
{
    public function image()
    {
        $img = DB::table('images')
            ->select('fish_name','filename', 'url')
            ->leftJoin('users', 'images.user_id', '=', 'users.id')
            ->orderBy('images.created_at', 'desc')
            ->first();
        return response()->json(
            $img
        );
    }
    public function fish_name() {
        $output = shell_exec("python /home/ubuntu/python/rockfish/rockfish/main.py");
        $a = strpos($output, '"');
        $result = substr($output,$a+1,-2);
        return $result;
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
         $fish_name = $this -> fish_name();
         $user = JWTAuth::parseToken()->authenticate();
         Image::create([
            'user_id'   => $user->id,
            'fish_name' => $fish_name,
            'filename'   => $name,
            'url' => $url
         ]);
    }
}
