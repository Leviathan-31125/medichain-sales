<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\TempSODTL;
use App\Models\TempSOMST;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempSODTLController extends Controller
{
    public function getSODTLbySONO($fc_sono) {
        $decode_sono = base64_decode($fc_sono);
        $TempSOMST = TempSOMST::find($decode_sono);

        if($TempSOMST){
            $TempSODTL = TempSODTL::where('fc_sono', $decode_sono)->with('stock')->get();
            return response()->json(['status' => 200, 'data' => $TempSODTL]);
        }
        
        return response()->json(['status' => 400, 'message' => 'Not Found! SO Detail gagal didapatkan'], 400);
    }

    public function addSODTL(Request $request, $fc_sono) {
        $validator = Validator::make($request->all(), [
            "fc_barcode" => 'required',
            "fc_statusbonus" => 'required',
            "fn_qty" => 'required',
            "fm_price" => 'required'
        ], [
            "fc_barcode.required" => "Kode barang tidak boleh kosong",
            "fc_statusbonus.required" => "Status bonus tidak ada",
            "fn_qty.required" => "Qty tidak boleh null atau 0",
            "fm_price.required" => "Harga tidak terdeteksi"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 300,
                'message' => $validator->errors()->first()
            ], 400);
        }
    
        $decode_sono = base64_decode($fc_sono);

        $TempSOMST = TempSOMST::find($decode_sono);
        if(!$TempSOMST) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak tersedia pada system'], 400);
        
        $request->merge(['fc_sono' => $decode_sono]);

        $checkStock = Stock::where('fc_barcode', $request->fc_barcode)->first();
        if(!$checkStock) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Stock tidak tersedia pada system'], 400);

        $request->fc_statusbonus == null? $request->merge(['fc_statusbonus' => 'F']) : $request;

        $checkSODTL = TempSODTL::where([
            'fc_barcode' => $request->fc_barcode,
            'fc_statusbonus' => $request->fc_statusbonus,
            'fc_sono' => $request->fc_sono
        ])->first();

        if($checkSODTL) 
            return response()->json(['status' => 400, 'message' => 'Duplicate Data! Stock sudah tersedia pada Sales Order'], 400);

        $addSODTL = TempSODTL::create($request->except([
            'fn_rownum', 'fc_namepack', 'fc_stockcode', 'fn_qty_do', 'fm_value'
        ]));

        if($addSODTL) 
            return response()->json(['status' => 201, 'message' => 'Stock berhasil ditambahkan']);
        return response()->json(['status' => 400, 'message' => 'Stock Gagal ditambahkan'], 400);
    }

    public function removeSODTL(Request $request, $fc_sono) {
        $decode_sono = base64_decode($fc_sono);
        if($request->fn_rownum == null || !TempSODTL::where([ 'fc_sono' => $decode_sono, 'fn_rownum' => $request->fn_rownum])->first()) 
            return response()->json(['status' => 400, 'message' => 'Detail stock tidak valid'], 400);

        $removeSODTL = TempSODTL::where([
            'fc_sono' => $decode_sono,
            'fn_rownum' => $request->fn_rownum
        ])->delete();

        if($removeSODTL) 
            return response()->json(['status' => 201, 'message' => 'Stock berhasil diremove']);

        return response()->json(['status' => 400, 'message' => 'Stock Gagal dihapus'], 400);
    }

    public function updateSODTL(Request $request, $fc_sono) {
        $decode_sono = base64_decode($fc_sono);

        $TempSOMST = TempSOMST::find($decode_sono);
        if(!$TempSOMST) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak tersedia pada system'], 400);

        if($request->fn_rownum == null || !TempSODTL::where([ 'fc_sono' => $decode_sono, 'fn_rownum' => $request->fn_rownum])->first()) 
            return response()->json(['status' => 400, 'message' => 'Detail stock tidak valid'], 400);

        $updateSODTL = TempSODTL::where([
            'fc_sono' => $decode_sono, 
            'fn_rownum' => $request->fn_rownum
        ])->update([
            'fm_discprice' => $request->fm_discprice,
            'fn_qty' => $request->fn_qty,
            'ft_description' => $request->ft_description
        ]);

        if($updateSODTL) 
            return response()->json(['status' => 201, 'message' => 'Stock berhasil diupdate']);

        return response()->json(['status' => 400, 'message' => 'Stock Gagal diupdate'], 400);
    }

    public function getAllStock(){
        $data = Stock::with('brand')->get();
        return response()->json($data);
    }
}
