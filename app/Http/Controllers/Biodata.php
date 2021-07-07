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


    public function update(Request $request) {
        if(!empty($request->file)){
            $this->validate($request, [
                'file' => 'required|max:2028'
            ]);

            $file = $request->file('file');
            $nama_file = time()."_".$file->getClientOriginalName();

            $tujuan_upload = 'data_file';

            $file->move($tujuan_upload,$nama_file);
            $data = DB::table('tbl_biodata')->where('id',$request->id)->get();

            foreach($data as $biodata) {
                @unlink(public_path('data_file/'.$biodata->gamber));
                $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'hobi' => $request->hobi,
                    'foto' => $nama_file
                ]);
                $res['message'] = 'success !';
                $res['values'] = $ket;
                return response($res);
            }
        } else {
            $data = DB::table('tbl_biodata')->where('id',$request->id)->get();

            foreach($data as $karyawan) {
                $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'hobi' => $request->hobi
                ]);
                $res['message'] = 'success !';
                $res['values'] = $ket;
                return response($res);
            }
        }
    }


    public function delete($id) {
        $data = DB::table('tbl_biodata')->where('id',$id)->get();
        foreach( $data as $biodata) {
            if(file_exists(public_path('data_file/'.$biodata->gambar))) {
                @unlink(public_path('data_file/'.$biodata->gamber));
                DB::table('tbl_biodata')->where('id', $id)->delete();
                $res['message'] = "success!";
                return response($res);
            } else {
                $res['message'] = "Empty !";
                return response($res);
            }

        }
    }
}
