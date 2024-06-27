<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerConstroller extends Controller
{
    public function getAllCustomer () {
        $data = Customer::with(['bank'])->get();
        return response()->json($data, 200);
    }

    public function getDetailCustomer($fc_membercode) {
        $membercode_decode = base64_decode($fc_membercode);

        $member = Customer::with('bank')->find($membercode_decode);
        if(!$member)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Customer tidak ditemukan pada system'], 400);
        return response()->json($member, 200);
    }

    public function createCustomer(Request $request) {
        $validator = Validator::make($request->all(), [
            'fv_membernpwp' => 'required',
            'fv_membername' => 'required',
            'fv_memberaddress' => 'required',
            'fv_memberaddress_loading' => 'required',
            'fc_picname1' => 'required',
            'fv_memberphone1' => 'required',
            'fc_typebusiness' => 'required',
            'fc_legalstatus' => 'required',
            'fc_branchtype' => 'required',
            'fc_membertaxcode' => 'required',
            'fc_memberpph' => 'required',
            'fv_npwpname' => 'required',
            'fv_npwpaddress' => 'required',
            'fm_doplaffon' => 'required',
            'fn_agingreceivable' => 'required',
            'fc_memberbank' => 'required',
            'fc_bankaccount' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
        }

        $create = Customer::create($request->all());
        if($create)
            return response()->json(['status' => 201, 'message' => 'Berhasil menambahkan customer'], 201);

        return response()->json(['status' => 400, 'message' => 'Create Fail! Gagal menambahkan customer'], 400);
    }

    public function updateCustomer(Request $request, $fc_membercode) {
        $validator = Validator::make($request->all(), [
            'fv_membernpwp' => 'required',
            'fv_membername' => 'required',
            'fv_memberaddress' => 'required',
            'fv_memberaddress_loading' => 'required',
            'fc_picname1' => 'required',
            'fv_memberphone1' => 'required',
            'fc_typebusiness' => 'required',
            'fc_legalstatus' => 'required',
            'fc_branchtype' => 'required',
            'fc_membertaxcode' => 'required',
            'fc_memberpph' => 'required',
            'fv_npwpname' => 'required',
            'fv_npwpaddress' => 'required',
            'fm_doplaffon' => 'required',
            'fn_agingreceivable' => 'required',
            'fc_memberbank' => 'required',
            'fc_bankaccount' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 400);
        }

        $membercode_decode = base64_decode($fc_membercode);

        $member = Customer::with('bank')->find($membercode_decode);
        if(!$member)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Customer tidak ditemukan pada system'], 400);

        $update = $member->update($request->except('fv_membernpwp'));
        if ($update)
            return response()->json(['status' => 201, 'message' => 'Berhasil emngupdate customer'], 201);
        return response()->json(['status' => 400, 'message' => 'Update Fail! Gagal mengupdate customer'], 400);
    }

    public function deleteCustomer($fc_membercode) {
        $membercode_decode = base64_decode($fc_membercode);

        $member = Customer::find($membercode_decode);
        if(!$member)
            return response()->json(['status' => 400, 'message' => 'NOt Found! Customer tidak ditemukan pada system'], 400);

        $delete = $member->delete();
        if($delete)
            return response()->json(['status' => 201, 'message' => 'Berhasil menghapus customer'], 201);
        return response()->json(['status' => 400, 'message' => 'Delete Fail! Gagal menghapus customer'], 400);
    }
}
