<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\TempSODTL;
use App\Models\TempSOMST;
use Illuminate\Http\Request;

class TempSODTLController extends Controller
{
    public function getSODTLbySONO($fc_sono) {
        $decode_sono = base64_decode($fc_sono);
        $TempSOMST = TempSOMST::find($decode_sono);

        if($TempSOMST){
            $TempSODTL = TempSODTL::where('fc_sono', $decode_sono)->with('stock')->get();
            return response()->json(['status' => 200, 'data' => $TempSODTL]);
        }
        
        return response()->json(['status' => 400, 'message' => 'Not Found! SO Detail gagal didapatkan']);
    }

    public function addSODTL(Request $request, $fc_sono) {
        $decode_sono = base64_decode($fc_sono);

        $TempSOMST = TempSOMST::find($decode_sono);
        if(!$TempSOMST) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak tersedia pada system']);
        
        $request->merge(['fc_sono' => $decode_sono]);

        $checkStock = Stock::where('fc_barcode', $request->fc_barcode)->first();
        if(!$checkStock) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Stock tidak tersedia pada system']);

        $request->fc_statusbonus == null? $request->merge(['fc_statusbonus' => 'F']) : $request;

        $checkSODTL = TempSODTL::where([
            'fc_barcode' => $request->fc_barcode,
            'fc_statusbonus' => $request->fc_statusbonus,
            'fc_sono' => $request->fc_sono
        ])->first();

        if($checkSODTL) 
            return response()->json(['status' => 400, 'message' => 'Duplicate Data! Stock sudah tersedia pada Sales Order']);

        $addSODTL = TempSODTL::create($request->except([
            'fn_rownum', 'fc_namepack', 'fc_stockcode', 'fn_qty_do', 'fm_price', 'fm_value'
        ]));

        if($addSODTL) 
            return response()->json(['status' => 201, 'message' => 'Stock berhasil ditambahkan']);
        return response()->json(['status' => 400, 'message' => 'Stock Gagal ditambahkan']);
    }

    public function removeSODTL(Request $request, $fc_sono) {
        $decode_sono = base64_decode($fc_sono);
        if($request->fn_rownum == null || !TempSODTL::where([ 'fc_sono' => $decode_sono, 'fn_rownum' => $request->fn_rownum])->first()) 
            return response()->json(['status' => 400, 'message' => 'Detail stock tidak valid']);

        $removeSODTL = TempSODTL::where([
            'fc_sono' => $decode_sono,
            'fn_rownum' => $request->fn_rownum
        ])->delete();

        if($removeSODTL) 
            return response()->json(['status' => 201, 'message' => 'Stock berhasil diremove']);

        return response()->json(['status' => 400, 'message' => 'Stock Gagal dihapus']);
    }

    public function updateSODTL(Request $request, $fc_sono) {
        $decode_sono = base64_decode($fc_sono);

        $TempSOMST = TempSOMST::find($decode_sono);
        if(!$TempSOMST) 
            return response()->json(['status' => 400, 'message' => 'Not Found! Sales Order tidak tersedia pada system']);

        if($request->fn_rownum == null || !TempSODTL::where([ 'fc_sono' => $decode_sono, 'fn_rownum' => $request->fn_rownum])->first()) 
            return response()->json(['status' => 400, 'message' => 'Detail stock tidak valid']);

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

        return response()->json(['status' => 400, 'message' => 'Stock Gagal diupdate']);
    }
}
