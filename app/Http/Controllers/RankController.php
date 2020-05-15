<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Ranking;
use Storage;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RankController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
    public function create(){
        return view('images.create');
    }
    public function rank()
    {
        $rank_of_fish = DB::table('rankings')
            ->select('rankings.id','name','fish_name', 'length','images.url','location','rankings.created_at')
            ->leftJoin('users', 'rankings.user_id', '=', 'users.id')
            ->join('images', 'rankings.user_id', '=', 'images.user_id')
            ->orderBy('rankings.length', 'desc')
            ->take(10)
            ->paginate(4);
        return response()->json(
            $rank_of_fish
        );
    }

    public function fish_name() {
        $output = shell_exec("python C:/Users/PC/jekim/rockfish/rockfish/main.py");
        $a = strpos($output, '"');
        $result = substr($output,$a+1,-2);
        return $result;
    }
    public function store(Request $request)
    {
        $fish_name = $this -> fish_name();
        $user = JWTAuth::parseToken()->authenticate();
        $ranking = new Ranking([
            'user_id'   => $user->id,
            'fish_name' => $fish_name,
            'length'    => $request->get('length'),
            'location'  => $request->get('location')
        ]);
        $ranking->save();
        return response()->json(['status' => 'success'], 200);
    }
    public function destroy($request, $id)
    {
        //토큰 확인
        $token = $request->header('authorization');
        if($token != ''){
            return response()->json([
                'message' => 'App key not found'
                ], 401);    
        }
        $rank = Ranking::findOrFail($id);
        $rank->delete();
        return response()->json([
            'status' => 'success'
            ], 200);    
    }
}
