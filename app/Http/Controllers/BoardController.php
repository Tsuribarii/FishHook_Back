<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Board;
use App\User;


class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //모델과 컨트롤러 연결
        $this->board_model = new Board();

        //사용자 권한 auth 미들웨어
        // $this->middleware('auth');
    }

    public function index()
    {
        //게시글 리스트 
        // $board =  Board::latest()->paginate(10);
        $board = DB::table('users')
        ->join('boards','users.id','=','boards.user_id')
        ->select('boards.id','user_id','tide','title','species','bait',
            'location','content','hits','sympathy','boards.created_at',
            'users.name')
        ->paginate(10);
        // ->get();
        return response()->json($board);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'species' => 'required',
            'tide' => 'required',
            'bait' => 'required',
            'location' => 'required',
            'content' => 'required',
        ]);

        return Board::create([
            'user_id'=>\Auth::id(),
            'title' => $request['title'],
            'species' => $request['species'],
            'tide' => $request['tide'],
            'bait' => $request['bait'],
            'location' => $request['location'],
            'content' => $request['content']
         ]);

         return response()->json([
            'status' => 'success'
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $board = Board::find($id);
        $board = DB::table('boards')
            ->join('users','boards.user_id','=','users.id')
            ->select('boards.id','user_id','tide','title','species','bait',
                'location','content','hits','sympathy','boards.created_at',
                'users.name')
            ->where('boards.id',$id)
            ->first();

        return response()->json($board);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $board = Board::where('id',$id)->first();
        return response()->json([
            'board'=>$board
        ]);
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
        $this->validate($request, [
            'title' => 'required',
            'species' => 'required',
            'tide' => 'required',
            'bait' => 'required',
            'location' => 'required',
            'content' => 'required',
        ]);

        $board = Board::findOrFail($id);

        $board->update($request->all());

        return response()->json([
            'status' => 'success'
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();
        return response()->json([
            'status' => 'success'
            ], 200);    
    }
}
