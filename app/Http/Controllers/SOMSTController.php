<?php

namespace App\Http\Controllers;

use App\Models\SOMST;
use Illuminate\Http\Request;

class SOMSTController extends Controller
{
    public function getAllSOMST() {
        $data = SOMST::with(['sodtl', 'customer', 'sales', 'sodtl.stock'])->get();
        return response()->json($data,200);
    }

    public function getDetailSOMST($fc_sono) {
        $sono_decode = base64_decode($fc_sono);
        $data = SOMST::with(['sodtl', 'customer', 'sales'])->find($sono_decode);

        if(!$data)
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak ditemukan'], 400);
        return response()->json($data, 200);
    }

    public function acceptRequest($fc_sono){
        $sono_decode = base64_decode($fc_sono);
        $data = SOMST::with(['sodtl', 'customer', 'sales'])->find($sono_decode);

        if(!$data)
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak ditemukan'], 400);

        $update = $data->update(['fc_status' => "SUBMIT"]);
        if ($update)
            return response()->json(['status' => 201, 'message' => 'SO berhasil diaccept'], 201);
        return response()->json(['status' => 400, 'message' => 'Accept Failed'], 400);
    }

    public function rejectRequest($fc_sono){
        $sono_decode = base64_decode($fc_sono);
        $data = SOMST::with(['sodtl', 'customer', 'sales'])->find($sono_decode);

        if(!$data)
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak ditemukan'], 400);

        $update = $data->update(['fc_status' => "REJECT"]);
        if ($update)
            return response()->json(['status' => 201, 'message' => 'SO berhasil direject'], 201);
        return response()->json(['status' => 400, 'message' => 'Reject Failed'], 400);
    }
}
