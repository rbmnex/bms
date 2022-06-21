<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\Bridge;
use App\Models\MasterLookup;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RatingExport implements FromView, ShouldAutoSize, WithHeadings
{
    use Exportable;
	private $ids = array();
    private $components;

	public function __construct($ids = array())
	{
		$this->ids = $ids;
        $this->components = MasterLookup::loadLookup('Bridge Component');
	}

    public function view(): View {

        $bridges = Bridge::whereIn('id',$this->ids)->orderBy('id','desc')->get();
        return view('exports.inspection',['bridges' => $bridges,'components' => $this->components]);
    }

    public function headings() : array
	{
        $array = ['Structure No.',
        'Bridge Name',
        'Road No.',
        'Road Name',
        'Section',
        'State',
        'District',
        'Year Built',
        'Spans',
        'Max Span',
        'Total Length',
        'Bridge Width',
        'Width Kerb to Kerb',
        'Skew',
        'Deck Type',
        'System Type',
        'Material Type'];

        foreach($this->components as $comp) {
            array_push($array,$comp);
        }
        return $array;
    }
}