<?php

namespace  App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringExport implements FromView, ShouldAutoSize
{
    private $datas;

    public function __construct($datas)
    {
        $this->datas = $datas;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('monitoring.excel', [
            'datas' => $this->datas,
        ]);
    }
}
