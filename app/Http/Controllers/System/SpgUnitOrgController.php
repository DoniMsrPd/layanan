<?php

namespace App\Http\Controllers\System;

use App\Models\System\SpgUnitOrg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\Datatables\Datatables;

class SpgUnitOrgController extends Controller
{
    public function datatables(Request $request)
    {
        set_time_limit(-1);
        $data = SpgUnitOrg::whereNull('TglEnd')->filtered();

        return dataTables::of($data)
            ->addColumn('pilih', function ($data) {
                return '<button
                        class="mr-2 mb-2 btn btn-primary pilih"
                        title="Pilih" title-pos="up"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_jabatan="' . $data->NmJabatanLengkap . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '">
                            '.btnPilih().'
                    </button>';
            })
            ->rawColumns(['pilih'])->make(true);
    }
}
