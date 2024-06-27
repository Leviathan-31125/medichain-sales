<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function getAllSales () {
        $data = Sales::with(['bank'])->get();
        return response()->json($data, 200);
    }

    public function getDetailSales($fc_salescode) {
        $salescode_decode = base64_decode($fc_salescode);

        $member = Sales::with('bank')->find($salescode_decode);
        if(!$member)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Sales tidak ditemukan pada system'], 400);
        return response()->json($member, 200);
    }

    public function createSales(Request $request) {
        $validator = Validator::make($request->all(), [
            'fc_salestype' => 'required',
            'fv_salesname' => 'required',
            'fc_saleslevel' => 'required',
            'fv_salesphone' => 'required',
            'fv_salesemail' => 'required',
            'fc_salesbank' => 'required',
            'fc_bankaccount' => 'required',
            'fc_status' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
        }

        $create = Sales::create($request->all());
        if($create)
            return response()->json(['status' => 201, 'message' => 'Berhasil menambahkan sales'], 201);

        return response()->json(['status' => 400, 'message' => 'Create Fail! Gagal menambahkan sales'], 400);
    }

    public function updateSales(Request $request, $fc_salescode) {
        $validator = Validator::make($request->all(), [
            'fc_salestype' => 'required',
            'fv_salesname' => 'required',
            'fc_saleslevel' => 'required',
            'fv_salesphone' => 'required',
            'fv_salesemail' => 'required',
            'fc_salesbank' => 'required',
            'fc_bankaccount' => 'required',
            'fc_status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
        }

        $salescode_decode = base64_decode($fc_salescode);

        $sales = Sales::with('bank')->find($salescode_decode);
        if(!$sales)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Sales tidak ditemukan pada system'], 400);

        $update = $sales->update($request->all());
        if ($update)
            return response()->json(['status' => 201, 'message' => 'Berhasil emngupdate sales'], 201);
        return response()->json(['status' => 400, 'message' => 'Update Fail! Gagal mengupdate sales'], 400);
    }

    public function deleteSales($fc_salescode) {
        $salescode_decode = base64_decode($fc_salescode);

        $sales = Sales::find($salescode_decode);
        if(!$sales)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Sales tidak ditemukan pada system'], 400);

        $delete = $sales->delete();
        if($delete)
            return response()->json(['status' => 201, 'message' => 'Berhasil menghapus sales'], 201);
        return response()->json(['status' => 400, 'message' => 'Delete Fail! Gagal menghapus sales'], 400);
    }
}
