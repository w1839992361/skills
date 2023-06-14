<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Size;
use Illuminate\Http\Request;

class FrameController extends Controller
{

    // getAll
    function getAllFrame()
    {

        $frames = Frame::all()->map(function ($item) {
            $item->size = Size::find($item->size_id)->size;
            $item->price /= 100;
            return $item;
        });

        return $this->successResponse($frames);
    }

    // update
    function updateFrameById(Request $req, $id)
    {
        $frame = Frame::find($id);
        if (!$frame) return $this->notFoundResponse();
        $frame->update(["price" => $req->get("price") * 100, "name" => $req->get("frame_name")]);
        return $this->successResponse($frame);
    }
}
