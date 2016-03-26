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

        if(!$request->model){
            return response()->json("Error Model undefined");
        }
        

        $req = $request;

        $limit = 10;
        $order = "desc";
        $orderBy = $req->model.".date_created";
        $model = "model";
        $select = "*";

        if($req->select){
            $select = explode(',', $req->select);
        }

        if($req->order){
            $order = $req->order;
        }

        if($req->orderBy){
            $orderBy = $req->orderBy;
        }


        $Qry = DB::table($req->model)
                    ->select($select);


        if($req->where){


            $isArrayWhere = explode(',', $req->where);

            if(is_array($isArrayWhere)){


                    $aryObj = array();
                    foreach ($isArrayWhere as $key => $item) {

                        $aryWhere = explode(' ', $item);

                        array_push($aryObj, array($aryWhere[0],$aryWhere[1],$aryWhere[2]));

                    }  

                    $Qry->where($aryObj);

            }else{
                $where = explode(' ', $req->where);
                $Qry->where($where[0], $where[1], $where[2]);

            }

        }

        if($req->join){


            $isArrayJoin = explode(',', $req->join);

            if(is_array($isArrayJoin)){


                    $aryObj = array();
                    foreach ($isArrayJoin as $key => $itemJoin) {

                        $aryJoin = explode(' ', $itemJoin);
                        $Qry->join($aryJoin[0],$aryJoin[1],$aryJoin[2],$aryJoin[3]);
                    }  

                    

            }else{
                $aryJoin = explode(' ', $req->join);
                $Qry->join($aryJoin[0],$aryJoin[1],$aryJoin[2],$aryJoin[3]);

            }

        }


        $Qry->skip($req->offset);
        $Qry->take($limit);
        $Qry->orderBy($orderBy, $order);

        $findModel = $Qry->get();


        return response()->json($findModel);
       
    }
}
