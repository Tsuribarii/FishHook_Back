<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FishingPlace;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class FishingPlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fishing()
    {
        //디비값 가져오기
        $all_data = DB::table('fishing_places')->get();

        // json 변환
        $xml_data = json_encode($all_data);
        $xml_data = json_decode($xml_data, true);

        // 배열로 변환
        $array = [];
        foreach ($xml_data as $data) {
            $data['main_fish_species'] = explode(',',$data['main_fish_species']);
            $data['main_fish_image']  = explode(',',$data['main_fish_image']);
            // 변환값 푸시
            array_push($array, $data);
        }
        $items = $this->paginate($array);

        return response()->json(
            $items
        );
    }
    public function paginate($items, $perPage = 9, $page = null, $options = []) { 
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1); 
        $items = $items instanceof Collection ? $items : Collection::make($items); 
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options); 
    }

    public function fishing_json()
    {
        //json 가져오기
        $path = '/home/ubuntu/python/FishHook_FishingSpot/FishingSpot.json';
        // $path = 'C:\Users\PC\jekim\FishHook_Back\storage\fishing.json';
        $datas = json_decode(file_get_contents($path), true);
        
        // key, value 지정
        $json = [];         
        for ($i=0; $i < count($datas); $i++) {

            $kk = array(
               'place_name'    => $datas[$i][2],
               'location'      => $datas[$i][8],
               'fishing_type'  => $datas[$i][4],
               'phone_number'  => $datas[$i][3],
               'people'        => $datas[$i][5],
               'available_time'  => $datas[$i][6],
               'homepage'  => $datas[$i][7],
               'place_photo'   => $datas[$i][0],  
               'main_fish_species'=> implode(",", $datas[$i][10]),
               'main_fish_image'  => implode(",", $datas[$i][9]),
               'price' => $datas[$i][11]

            );
            array_push($json, $kk);
        }
        // 모델 가지고 오기
        $fishing = new FishingPlace();
        // 키밸류로 만든 array를 돌려서 데이터베이스에 삽입
        foreach ($json as $index => $value) {
           if($index < 1000){
               $fishing->place_name = $value['place_name'];
               $fishing->location = $value['location']; 
               $fishing->fishing_type = $value['fishing_type'];
               $fishing->phone_number = $value['phone_number'];
               $fishing->people = $value['people'];
               $fishing->available_time = $value['available_time'];
               $fishing->homepage = $value['homepage'];
               $fishing->main_fish_species = $value['main_fish_species'];
               $fishing->main_fish_image = $value['main_fish_image'];
               $fishing->price = $value['price'];
               $fishing->save();
               $fishing = new FishingPlace();
           }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


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
        //
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
}