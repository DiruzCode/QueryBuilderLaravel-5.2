<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class QueryBuilderController extends Controller
{


    public function index(Request $request)
    {   
        $req = $request;

        if($req->model){
            $limit = 10;
            $order = "desc";
            $orderBy = "date_created";
            $model = "model";
            $select = "*";

            if($req->limit){
                $limit = $req->limit;
            }

            if($req->select){
                $select = explode(',', $req->select);
            }

            if($req->order){
                $order = $req->order;
            }

            if($req->orderBy){
                $orderBy = $req->orderBy;
            }

            if($req->where){


                $isArrayWhere = explode(',', $req->where);

                if(is_array($isArrayWhere)){


                        $aryObj = array();
                        foreach ($isArrayWhere as $key => $item) {

                            $aryWhere = explode(' ', $item);

                            array_push($aryObj, array($aryWhere[0],$aryWhere[1],$aryWhere[2]));

                        }  

                        $findModel = DB::table($req->model)
                            ->select($select)
                            ->where($aryObj)
                            ->skip($req->offset)
                            ->take($limit)
                            ->orderBy($orderBy, $order)
                            ->get();   
                               
                }else{
                    $where = explode(' ', $req->where);

                    $findModel = DB::table($req->model)
                            ->select($select)
                            ->where($where[0], $where[1], $where[2])
                            ->skip($req->offset)
                            ->take($limit)
                            ->orderBy($orderBy, $order)
                            ->get(); 
                }

            }else{  

                $findModel = DB::table($req->model)   
                        ->select($select)
                        ->skip($req->offset)
                        ->take($limit)
                        ->orderBy($orderBy, $order)
                        ->get();

            }


             return response()->json($findModel);

        }else{

            return response()->json("Error Model undefined");

        }
       
    }
}
