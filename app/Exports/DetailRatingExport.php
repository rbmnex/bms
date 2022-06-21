<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use App\Models\MasterInspection;
use App\Models\MasterLookup;
use App\Models\Substructure;
use App\Models\Bridge;
use App\Models\Geometry;

class DetailRatingExport implements FromView, ShouldAutoSize 
{
    use Exportable;
	private $ids;
	

	public function __construct($ids)
	{
		$this->ids = $ids;
	}

	public function view(): View 
    {
        $master = MasterInspection::find($this->ids);
        $master->loadMissing('components');
        $components = $master->components;
        $bridge = Bridge::find($master->bridge_id);
        $bridge->loadMissing('road');
        $bridge->loadMissing('district');
        $road = $bridge->road;
        $year = $bridge->years->first();
        $year->loadMissing('superstructures');
        $count = 0;
        $deck = '';
        foreach($year->superstructures as $superstructure) {
            if($count) {
                if(isset($superstructure->deck)) {
                    $deck = $deck . ' AND ' . $superstructure->deck->name;
                }
            } else {
                $deck = $superstructure->deck->name;
            }
            $count = $count + 1;
        }
        $superstructure =  $year->superstructures->first();
        $year->loadMissing('geometry');
        $geometry = Geometry::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id]])->first();
        $abutment = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::ABUTMENT]])->first();
        $pier = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::PIER]])->first();
        $items = MasterLookup::loadLookup('Bridge Component');
        return view('exports.rating',compact('master','year','bridge','deck','superstructure','geometry','pier','road','abutment','components','items'));
    }
}