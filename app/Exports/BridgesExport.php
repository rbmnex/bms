<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Bridge;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/**
 * 
 */
class BridgesExport implements FromView, ShouldAutoSize, WithEvents, WithHeadings
{
	use Exportable;
	private $ids = array();
	

	public function __construct($ids = array())
	{
		$this->ids = $ids;
	}

	public function view(): View
	{
		$bridges = Bridge::fetch($this->ids);
		return view('exports.bridge', ['bridges' => $bridges]);
	}

	public function registerEvents(): array
	{
		return [
			BeforeWriting::class => function (BeforeWriting $event) {
				$event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A3);
				$event->getWriter()->getDelegate()->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
			},
			AfterSheet::class => function (AfterSheet $event) {
				$event->sheet->getStyle('H')->getAlignment()->setWrapText(true);
			}
		];
	}

	public function headings() : array
	{
		return [
			"Structure No.",
			"Bridge Name",
			"Road No.",
			"Road Name",
			"Section",
			"State",
			"District",
			"Year Built",
			"Spans",
			"Max Span",
			"Total Length",
			"Bridge Width",
			"Width Kerb to Kerb",
			"Skew",
			"Deck Type",
			"System Type",
			"Material Type",
			"Type",
			"Remark",
		];
	}
}
