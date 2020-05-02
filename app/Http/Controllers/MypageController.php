<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Ship;
use Auth;
use App\ShipRental;

class MypageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //모델과 컨트롤러 연결
        $this->user_model = new User();
        // $this->middleware('jwt.auth');

    }

    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = User::where('id',1)->first();
        return response()->json([
            'user'=>$user,
        ]);
    }

    public function checkshow()
    {
        $user = User::where('id',1)->first();
        $rental = DB::table('ship_rentals')
            ->join('users', 'users.id', '=', 'ship_rentals.user_id')->get();
        
            return response()->json([
            'user'=>$user,
            // 'ship'=>$ship,
            'rental'=>$rental
        ]);
    }

    /**edit
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::where('id',1)->first();
        return response()->json([
            'user'=>$user,
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { 
        $this->validate($request, [
            'password' => 'required',
            'phone_number' => 'required',
        ]);
        
        $user = User::findOrFail($id);
      
          if ($request->hasFile('profile_photo')) {
              $image = $request->file('profile_photo');
              $name = $image->getClientOriginalName();
              $destinationPath = public_path('/images');
              $imagePath = $destinationPath. "/".  $name;
              $image->move($destinationPath, $name);
              $user->profile_photo = $name;
            }
            
            $user->update($request->all());
            // $user->save();
            
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
    public function destroy()
    {
        $user = User::where('id',1)->first()
            // ->where('profile_photo')
            ->update(['profile_photo'=>'default.jpg']);
            
        return response()->json([
            'status' => 'success'
            ], 200);
    }
}
