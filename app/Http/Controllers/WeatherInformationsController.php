<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WeatherInformation;
use App\TideLocation;

class WeatherInformationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function weather($id)
    {
        $tide_location = TideLocation::where('id',$id)->first();
        $weather = WeatherInformation::where('location',$tide_location->location)->get();
        return response()->json(
            $weather
        );
    }
    //json -> database 저장함수  
    public function weather_json()
    {
        //json 가져오기
        $path = '/home/ubuntu/python/FishHook_Weather/weather.json';
        $json = trim(file_get_contents($path), "\xEF\xBB\xBF");
        $datas = json_decode($json, true);  
        // key, value 지정
        $json = [];
        for ($i=0; $i < count((is_countable($datas) ? $datas : [])); $i++) {
            $kk = array(
                'location'   => $datas[$i][0],
                'time' => $datas[$i][1],
                'weather_status'  => $datas[$i][2],
                'temperature'  => $datas[$i][3],
                'wind_direction' => $datas[$i][4],
                'wind_speed'  => $datas[$i][5],
                'wave_height'  => $datas[$i][6],
                'wave_direction'   => $datas[$i][7],
                'wave_period' => $datas[$i][8],
                'humidity'  => $datas[$i][9]
            );
            array_push($json, $kk);
        }
        // 모델 가져오기
        $weather = new WeatherInformation();
        // 키밸류로 만든 array를 돌려서 데이터베이스에 삽입
        foreach ($json as $res) {
            $weather->location      = $res['location'];
            $weather->time      = $res['time'];
            $weather->weather_status    = $res['weather_status'];
            $weather->temperature     = $res['temperature'];
            $weather->wind_direction      = $res['wind_direction'];
            $weather->wind_speed      = $res['wind_speed'];
            $weather->wave_height    = $res['wave_height'];
            $weather->wave_direction     = $res['wave_direction'];
            $weather->wave_period      = $res['wave_period'];
            $weather->humidity      = $res['humidity'];
            $weather->save();
            $weather = new WeatherInformation();
        }
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
