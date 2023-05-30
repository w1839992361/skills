<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    //
    function getAllSize(){
        $size = Size::all()->map(function ($item){
            $item->price /= 100;
            return $item;
        });
        return $this->successResponse($size);
    }

    function updateSize(Request $req,$id){
        $size = Size::find($id);
        if (!$size) return $this->notFoundResponse();

        if($req->get("price") <0) return $this->dataUnprocessedResponse();

        $size->update(["price"=>$req->get("price")*100]);

        return $this->successResponse($size);
    }
}
