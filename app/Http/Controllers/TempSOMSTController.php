<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sales;
use App\Models\TempSODTL;
use App\Models\TempSOMST;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TempSOMSTController extends Controller
{
    public function getAllTempSOMST(){
        $data = TempSOMST::with('tempsodtl')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function createTempSOMST(Request $request) {
        $TempSoMst = TempSOMST::find($request->fc_sono);
        $customer = Customer::find($request->fc_membercode);
        $sales = Sales::find($request->fc_salescode);

        if($TempSoMst)
            return response()->json(['status' => 400, 'message' => 'Duplicate Data! User yang sama sedang membuat Sales Order']);
        
        if(!$customer || !$sales)
            return response()->json(['status' => 400, 'message' => 'Invalid Data! Customer atau Sales tidak valid']);
        
        $data = TempSOMST::create([
            'fc_sono' => $request->fc_sono,
            'fc_sotype' => $request->fc_sotype,
            'fc_membercode' => $request->fc_membercode,
            'fc_salescode' => $request->fc_salescode,
        ]);

        if($data) 
            return response()->json(['status' => 201, 'message' => 'SO berhasil dibuat']);

        return response()->json(['status' => 400, 'message' => 'Create Fail! Maaf Sales Order gagal dibuat']);
    }

    public function detailTempSOMST ($fc_sono) {
        $sono_decoded = base64_decode($fc_sono);
        $data = TempSOMST::with('tempsodtl')->find($sono_decoded);

        if($data)
            return response()->json(['status' => 200, 'data' => $data]);

        return response()->json(['status' => 400, 'message' => 'Data Not Found! Sales Order tidak tersedia di System']);
    }
    
    public function setDetailInfoTempSOMST (Request $request, $fc_sono) {
        $sono_decoded = base64_decode($fc_sono);
        $data = TempSOMST::find($sono_decoded);

        if(!$data)
            return response()->json(['status' => 400, 'message' => 'Data Not Found! Sales Order tidak tersedia di System']);

        $updated = $data->update([
            'fd_sodate_user' => $request->fd_sodate_user,
            'fd_soexpired' => $request->fd_soexpired,
            'fm_downpayment' => $request->fm_downpayment == null? 0 : $request->fm_downpayment,
            'ft_description' => $request->ft_description
        ]);

        if ($updated) 
            return response()->json(['status' => 201, 'message' => 'SO berhasil diupdate']);

        return response()->json(['status' => 400, 'message' => 'Update Fail! Maaf Sales Order gagal diupdate']);
    }

    public function submitTempSOMST ($fc_sono) {
        $sono_decoded = base64_decode($fc_sono);
        $data = TempSOMST::find($sono_decoded);
        
        if(!$data)
            return response()->json(['status' => 400, 'message' => 'Data Not Found! Sales Order tidak tersedia di System']);

        DB::beginTransaction();

        try{
            $data->fc_status = 'SUBMIT';
            $data->fd_sodate_system = Carbon::now();
            $data->save();

            $deletedSODTL = TempSODTL::where('fc_sono', $sono_decoded)->delete();
            $deletedSOMST = TempSOMST::where('fc_sono', $sono_decoded)->delete();
            
            DB::commit();

            if($deletedSODTL && $deletedSOMST)
                return response()->json(['status' => 201, 'message' => 'Sales Order berhasil disubmit']);
        } catch(Exception $err) {
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => 'Create Failed! Sales Order gagal dibuat'.$err->getMessage()]);
        }
    }
}
