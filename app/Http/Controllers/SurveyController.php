<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Survey;
use Illuminate\Support\Facades\Http;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;


class SurveyController extends Controller
{

    public function indexall(Request $request)
    {
        try {
            $tableusers = Survey::all();
    
            // Mengubah format data menggunakan map()
            $modifiedData = $tableusers->map(function ($item) {
                return [
                    'pekerjaan' => $item->pekerjaan,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'umur' => $item->umur,
                    'informasi' => $item->informasi,
                    'created_at' => $item->created_at
                ];
            });
    
            return response()->json($modifiedData);
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => 'An error occurred while processing the request'], 500);
        }
    }

    public function downloadcsv(Request $request)
    {
        try {

                $tableusers = Survey::all();

                // Data yang akan diubah menjadi CSV
                $csvData = [];

                foreach ($tableusers as $item) {
                    $csvData[] = [
                        'pekerjaan' => $item->pekerjaan,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'umur' => $item->umur,
                        'informasi' => $item->informasi,
                        'created_at' => $item->created_at,
                    ];
                }

                // Menyimpan data ke dalam file CSV
                $csvFilePath = public_path('survey.csv');
                $csvFile = fopen($csvFilePath, 'w');
                fputcsv($csvFile, array_keys($csvData[0])); // Menulis header kolom
                foreach ($csvData as $row) {
                    fputcsv($csvFile, $row); // Menulis data baris
                }
                fclose($csvFile);

                // Memberikan respon dengan file CSV
                return Response::download($csvFilePath, 'survey.csv')->deleteFileAfterSend(true);

        } catch (Throwable $e) {
            report($e);

            return response()->json();
        }
    }

    public function store(Request $request)
    {
        try {
            $saveloc = Survey::create([
                'pekerjaan' => $request->input('pekerjaan'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'umur' => $request->input('umur'),
                'informasi' => $request->input('informasi'),
            ]);
    
            return response()->json(['message' => 'Data saved successfully'], 201);
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
