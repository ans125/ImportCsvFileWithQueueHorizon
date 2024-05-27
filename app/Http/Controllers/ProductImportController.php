<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Jobs\ProductCSVData;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\Request;
use Exception;

class ProductImportController extends Controller
{
    public function index(): View
    {
        return view('productImport');
    }

    public function store(Request $request)
    {
        try {
            if ($request->hasFile('csv')) {
                $csv = file($request->file('csv'));  // Correctly get the file path
                $chunks = array_chunk($csv, 500);

                $header = [];

                $batch = Bus::batch([])->dispatch();
                foreach ($chunks as $key => $chunk) {
                    $data = array_map('str_getcsv', $chunk);

                    if ($key == 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }
                    $batch->add(new ProductCSVData($data, $header));
                }
            }

            return redirect()->route('products.import.index')
                ->with('success', 'CSV import added to the queue, will update you once done');
        } catch (Exception $e) {
            return redirect()->route('products.import.index')
                ->with('error', 'An error occurred while processing the CSV file: ' . $e->getMessage());
        }
    }
}
