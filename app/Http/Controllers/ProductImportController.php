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
                $csv = file($request->file('csv'));
                $chunks = array_chunk($csv, 500);
                $header = [];
                $batch = Bus::batch([])->dispatch();

                $totalJobs = count($chunks);

                foreach ($chunks as $key => $chunk) {
                    $data = array_map('str_getcsv', $chunk);

                    if ($key == 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }

                    $batch->add(new ProductCSVData($data, $header));
                }

                return response()->json(['success' => true, 'total_jobs' => $totalJobs, 'batch_id' => $batch->id]);
            }
            
            return response()->json(['success' => false, 'message' => 'No CSV file provided']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the CSV file: ' . $e->getMessage()]);
        }
    }

    public function batchProgress($batchId)
    {
        $batch = Bus::findBatch($batchId);
        if (!$batch) {
            return response()->json(['error' => 'Batch not found'], 404);
        }

        $completedJobs = $batch->processedJobs();
        $totalJobs = $batch->totalJobs;

        return response()->json(['completed_jobs' => $completedJobs, 'total_jobs' => $totalJobs]);
    }
}
