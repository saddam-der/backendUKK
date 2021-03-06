<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MasyarakatController extends Controller
{
    public function GET_masyarakat()
    {
        $masyarakat = Masyarakat::all();

        return response()->json([
            'success' => true,
            'message' =>'List Semua Masyarakat',
            'data'    => $masyarakat
        ], 200);
    }

    public function POST_masyarakat(Request $request)
    {
        $this->validate($request, [
            'nama'   => 'required',
            'username' => 'required|unique:masyarakats|max:255',
            'password' => 'required|min:6',
            'telp'   => 'required',
        ]);
 
        $nama = $request->input("nama");
        $username = $request->input("username");
        $password = $request->input("password");
        $telp = $request->input("telp");
 
        $hashPwd = Hash::make($password);
 
        $data = [
            "nama" => $nama,
            "username" => $username,
            "password" => $hashPwd,
            "telp" => $telp,
        ];
 
        if (Masyarakat::create($data)) {
            $out = [
                "message" => "Register Berhasil!",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "Register Gagal!",
                "code"   => 404,
            ];
        }
 
        return response()->json($out, $out['code']);
    }

    public function PUT_masyarakat(Request $request, $id)
    {
        $nama = $request->input("nama");
        $password = $request->input("password");
        $telp = $request->input("telp");
 
        $hashPwd = Hash::make($password);
 
        $data = [
            "nama" => $nama,
            "password" => $hashPwd,
            "telp" => $telp
        ];

        $masyarakat = Masyarakat::where('nik', $id)->update($data);

        if ($masyarakat) {
            return response()->json([
                'code' => 200,
                'message' => 'Masyarakat Berhasil Diupdate!',
                'data' => $masyarakat
            ], 201);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'Masyarakat Gagal Diupdate!',
            ], 400);
        }
    }

    public function DELETE_masyarakat($id)
    {
        $masyarakat = Masyarakat::where('nik', $id)->first();
		
        if (!$masyarakat) {
            $out = [
                "message" => "masyarakat Gagal Dihapus!",
                "code"    => 401,
            ];
            return response()->json($out, $out['code']);
        } else {
            $masyarakat->delete();
            $out = [
                "message" => "masyarakat Berhasil Dihapus!",
                "code"    => 200,
            ];
            return response()->json($out, $out['code']);
        }
    }

}