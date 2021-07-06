<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\tbl_biodata;

class Biodata extends Controller
{
    public function getData() {
        $data = DB::table('tbl_biodata')->get();
        if(count($data) > 0) {
            $res['message'] = "Success!";
            $res['value'] = $data;
            return response($res);
        } else {
            $res['message'] = "Empty!";
            return response($res);
        }
    }


    public function store(Request $request) {
        $this->validate($request, [
            'file' => 'required|max:2048'
        ]);

        $file = $request->file('file');
        $nama_file = time()."_".$file->getClientOriginalName();

        $tujuan_upload = 'data_file';

        if($file->move($tujuan_upload,$nama_file)){
            $data = tbl_biodata::create(
                [
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'hobi' => $request->hobi,
                    'foto' => $nama_file
                ]
            );
            $res['message'] = 'success !';
            $res['values'] = $data;
            return response($res);
        }

    }
}
