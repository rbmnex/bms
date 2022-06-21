<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\BridgesExport;
use App\Exports\RatingExport;
use App\Exports\DetailRatingExport;
use Maatwebsite\Excel\Excel as Excel;
use App\Models\MasterLookup;
use App\Models\MasterInspection;
use App\Models\Bridge;
use App\Models\Substructure;
use App\Models\Geometry;

class FileExportController extends Controller
{
    //

    public function exportBridge(Request $request) {
        $extension = ".pdf";
        $ids = json_decode($request->ids);
        return (New BridgesExport($ids))->download("bridge_directory".$extension,Excel::DOMPDF);
    }

    public function exportRating(Request $request) {
        $extension = ".xlsx";
        $ids = json_decode($request->ids);
        /*
        $bridges = Bridge::whereIn('id',$ids)->orderBy('id','desc')->get();
        $components = MasterLookup::loadLookup('Bridge Component');
        return view('exports.inspection',compact('bridges','components'));
        */

        // return (New RatingExport($ids))->download("bridge_rating".$extension,Excel::XLSX);
        $components = MasterLookup::loadLookup('Bridge Component');
        $bridges = Bridge::whereIn('id',$ids)->orderBy('id','desc')->get();
        return view('exports.inspection',['bridges' => $bridges,'components' => $components]);
    }

    public function exportDetailRating(Request $request) {
        $extension = ".xlsx";
        $id = $request->id;
        // return (New DetailRatingExport($id))->download("detail_rating".$extension,Excel::XLSX);

        $master = MasterInspection::find($id);
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
