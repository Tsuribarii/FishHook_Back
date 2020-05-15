<?php

namespace App\Http\Controllers;

// use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\DB;
use App\User;
use App\Ship;
use App\ShipOwner;
use Auth;
use App\ShipRental;
use Illuminate\Support\Arr;

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
        $user = Auth::user();
        // $user = Auth::find($id);
        return response()->json($user);
    }

    // 승인 전 예약 리스트
    public function apply()
    {
        if(Auth::user()->roles=='2'){
            
            // ship 테이블에서 ship_id 데려옴
            $ship_id = DB::table('ship_owners')
                ->join('ships','ship_owners.id','=','ships.owner_id')
                ->where('ship_owners.user_id',Auth::id())
                ->select('ships.id')
                ->get();
            // return $ship_id;

            //where 넣을 수 있게 배열로
            $collection = collect($ship_id);
            $plucked = $collection->pluck('id');
            $plucked->all();
            // return $plucked;

            $rental = DB::table('ship_rentals')
                ->whereIn('ship_id',$plucked)
                //승인된 선박만
                ->where('ship_rentals.confirm',0)
                ->join('users','ship_rentals.user_id','=','users.id')
                ->select('ship_rentals.id','ship_id','departure_date','number_of_people','ship_rentals.created_at','users.name')
                ->get();
            
            return response()->json($rental);
        }
    }

    //승인 후 예약 현황
    public function checkshow()
    {
        //판매자의 예약현황 (예약정보, 유저정보)
        if(Auth::user()->roles=='2'){
            
            // ship 테이블에서 ship_id 데려옴
            $ship_id = DB::table('ship_owners')
                ->join('ships','ship_owners.id','=','ships.owner_id')
                ->where('ship_owners.user_id',Auth::id())
                ->select('ships.id')
                ->get();
            // return $ship_id;

            //where 넣을 수 있게 배열로
            $collection = collect($ship_id);
            $plucked = $collection->pluck('id');
            $plucked->all();
            // return $plucked;

            $rental = DB::table('ship_rentals')
                ->whereIn('ship_id',$plucked)
                //승인된 선박만
                ->where('ship_rentals.confirm',1)
                ->join('users','ship_rentals.user_id','=','users.id')
                ->select('ship_rentals.id','ship_id','departure_date','number_of_people','ship_rentals.created_at','users.name')
                ->get();
            
            return response()->json($rental);

        // 일반유저의 예약현황 (예약정보, 배정보)
        }else{

            $rental = DB::table('ships')
                ->where('ship_rentals.user_id',Auth::id())
                //승인된 선박만
                ->where('ship_rentals.confirm',1)
                ->join('ship_rentals','ships.id','=','ship_rentals.ship_id')
                ->join('ship_owners','ships.owner_id','=','ship_owners.id')
                ->select('ship_rentals.id','ship_id','departure_date','number_of_people','cancel',
                         'ship_rentals.created_at','ships.name','ships.departure_time','ships.cost','ship_owners.owner_name')
                ->get();

            $timenow = date("Y-m-d");
            $newRental = array();       
            $i=0;

            foreach($rental as $data){
                // departure_date 날짜 형식으로 바꾸기
                $timetarget = date("Y-m-d",strtotime($data->departure_date));       
                // 현재시간
                $str_now = strtotime($timenow);                  
                // 예약 시간                    
                $str_target = strtotime($timetarget);                               

                $newRental[$i]['id'] = $data->id;
                $newRental[$i]['ship_id'] = $data->ship_id;
                $newRental[$i]['departure_date'] = $data->departure_date;
                $newRental[$i]['departure_time'] = $data->departure_time;
                $newRental[$i]['number_of_people'] = $data->number_of_people;
                $newRental[$i]['created_at'] = $data->created_at;
                $newRental[$i]['name'] = $data->name;
                $newRental[$i]['cost'] = $data->cost;
                $newRental[$i]['owner_name'] = $data->owner_name;
                        
                if($data->cancel ==0){
                    // 현재 시간과 비교
                    if($str_now > $str_target){     
                        $newRental[$i]['status'] = "이용 완료";
                    }else{
                        $newRental[$i]['status'] = "이용 예정";
                    }
                }else{
                    $newRental[$i]['status'] = "취소 환불";
                }
            $i++;
        }
        return response()->json($newRental);
        }
    }

    public function status(){

        // 일반유저의 예약 현황 (전체)
        if(Auth::user()->roles=='1'){
            
            $time = DB::table('ship_rentals')
                ->where('user_id',Auth::id())
                ->join('users','ship_rentals.user_id','=','users.id')
                ->select('departure_date','cancel')
                ->get();
            
            $all = count($time);
            $reserve = 0;
            $complete = 0;
            $cancel = 0;
            $newRental = array();  

            foreach($time as $data){
                // 현재시간
                $timenow = date("Y-m-d");
                // departure_date 날짜 형식으로 바꾸기
                $timetarget = date("Y-m-d",strtotime($data->departure_date));  
                // 현재시간 형식
                $str_now = strtotime($timenow);                  
                // 예약 시간 형식                 
                $str_target = strtotime($timetarget);
            
            if($data->cancel ==0){
                // 현재 시간과 비교
                if($str_now > $str_target){    
                    $complete += 1; 
                }else{
                    $reserve += 1;
                }
            }else{
                $cancel += 1;
            }
            // $i++;
            }
        $newRental = array('all'=>$all,'reserve'=>$reserve,'complete'=>$complete,'cancel'=>$cancel);
        return response()->json($newRental);
        }
    }

    /**edit
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::find(Auth::user()->id);
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
        // \Log::debug($request->all());
        $this->validate($request, [
            'password' => 'required',
            'phone_number' => 'required',
        ]);
        
        $user = User::find(Auth::user()->id);
        $currentphoto = $user->profile_photo;
          if ($request->photo != $currentphoto) {
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
        $user = User::find(Auth::user()->id)
            // ->where('profile_photo')
            ->update(['profile_photo'=>'default.jpg']);
            
        return response()->json([
            'status' => 'success'
            ], 200);
    }
}
