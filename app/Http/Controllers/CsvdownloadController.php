<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Payments;


class CsvdownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function indexpayment(Request $request)
    {
        try {

            $payments = Payments::all();

            $csvData = [];
            $csvData[] = ['id', 'lat', 'lon', 'province', 'region', 'status', 'email', 'name', 'paket', 'timetopayment', 'exp', 'created_at', 'updated_at'];

            foreach ($payments as $item) {
                $status = $item->status;
		    
                $user = DB::table('users')->where('id', $item->user_id)->first();        
                        // Tambahkan kondisi untuk mengubah status menjadi "Cancel" jika tanggal saat ini kurang dari 2 hari dari tanggal kadaluwarsa
                        $currentDate = Carbon::now();
                        $expDate = Carbon::parse($item->lastday)->subDays(2);
                        
                        // Tambahkan kondisi untuk mengubah status menjadi "Cancel" jika statusnya "Pending" dan created_at kurang dari 1 hari yang lalu
                        if ($status == "Pending" && $currentDate->diffInDays($item->created_at) > 1) {
                            $status = "Cancel";
                            $daysDiff = 'None';
                        }
                        elseif ($status == "Pending" && $currentDate->diffInDays($item->created_at) < 1)  {
                            $timeDiff = $currentDate->diff($item->created_at);
                            $hoursDiff = $timeDiff->h;
                            $minutesDiff = $timeDiff->i;
                            $secondsDiff = $timeDiff->s;
        
                            
                            if ($hoursDiff > 0){
                                $daysDiff = [
                                    'hours' => 24 - $hoursDiff,
                                    'minutes' => 60 -$minutesDiff,
                                    'seconds' => 60 - $secondsDiff,
                                ];
                            }else{
                                $daysDiff = [
                                    'hours' => 23 - $hoursDiff,
                                    'minutes' => 60 -$minutesDiff,
                                    'seconds' => 60 - $secondsDiff,
                                ];
                            }
                        } else {
                            $daysDiff = 'None';
                        }
                        $expDate = Carbon::parse($item->lastday);
                        $createdAt = Carbon::parse($item->created_at);
                        $paket = $createdAt->diffInDays($expDate);
                $csvData[] = [
                    $item->uuid,
                    (float)$item->lat,
                    (float)$item->lon,
                    $item->province,
                    $item->region,
                    $status,
                    $user->email,
                    $user->name,
                    $paket + 1,
                    $daysDiff,
                    $item->lastday,
                    $item->created_at,
                    $item->updated_at,
                ];
            }

            // Create a CSV buffer in memory
            $csvBuffer = fopen('php://temp', 'w');

            // Write the CSV data to the buffer
            foreach ($csvData as $data) {
                // Convert all elements of the $data array to strings
                $row = [];
                foreach ($data as $value) {
                    if (is_array($value) || is_object($value)) {
                        // If the value is an array or object, convert it to a JSON string
                        $row[] = json_encode($value);
                    } else {
                        // Otherwise, convert it to a string
                        $row[] = strval($value);
                    }
                }

                // Write the CSV row to the buffer
                fputcsv($csvBuffer, $row);
            }

            // Reset the pointer to the beginning of the file
            rewind($csvBuffer);

            // Create a response with the CSV content
            $response = response()->stream(function () use ($csvBuffer) {
                fpassthru($csvBuffer);
            }, 200);

            $response = Response::make(stream_get_contents($csvBuffer));
            $response->header('Content-Disposition', 'attachment; filename=payment.csv');
            $response->header('Content-Type', 'text/csv');

            return $response;
        } catch (Throwable $e) {
            report($e);
            return response()->json();
        }
    }

    public function indexadmin(Request $request)
    {
        try {

                $tableusers = User::all();

                // Data yang akan diubah menjadi CSV
                $csvData = [];

                foreach ($tableusers as $item) {
                    $csvData[] = [
                        'uuid' => $item->uuid,
                        'name' => $item->name,
                        'email' => $item->email,
                        'status' => $item->status,
                        'email_verified_at' => $item->email_verified_at,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }

                // Menyimpan data ke dalam file CSV
                $csvFilePath = public_path('users.csv');
                $csvFile = fopen($csvFilePath, 'w');
                fputcsv($csvFile, array_keys($csvData[0])); // Menulis header kolom
                foreach ($csvData as $row) {
                    fputcsv($csvFile, $row); // Menulis data baris
                }
                fclose($csvFile);

                // Memberikan respon dengan file CSV
                return Response::download($csvFilePath, 'users.csv')->deleteFileAfterSend(true);

        } catch (Throwable $e) {
            report($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function historisbulananCsv(Request $request)
    {
        $host = env('HOST');

        $url = "http://" . $host . ":8080/api/search";
        $lokasi = $request->query('lokasi');
        $lon = $request->query('lon');
        $lat = $request->query('lat');

        if (floatval($lat) < 0) {
            $arah = "LS";
        } elseif (floatval($lat) > 0) {
            $arah = "LU";
        }
        // Memilih sheet aktif
        $zz = abs(floatval($lat));

        $index_names = [
            "potensi-bulanan",
            "kecepatan-angin-bulanan",
            "kecepatan-angin-maksimum-bulanan",
            "arah-angin-bulanan",
            "temperature-bulanan",
            "temperature-maximum-bulanan",
            "tutupan-awan-total-bulanan",
            "tutupan-awan-tinggi-bulanan",
            "tutupan-awan-menengah-bulanan",
            "tutupan-awan-rendah-bulanan",
            "sunrise-bulanan",
            "sunset-bulanan",
            "arah-matahari-zenith-bulanan",
            "curah-hujan-bulanan",
            "indeks-kebeningan-bulanan",
        ];

        function generateJsonPayload($name_index, $lat, $lon)
        {
            return [
                "distance" => "10km",
                "lat" => $lat,
                "lon" => $lon,
                "year" => 2023,
                "nameindex" => $name_index,
                "time" => "bulanan"
            ];
        }

        $bulan_list = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // Generate the CSV data
        $csv_data = [];
        $csv_data[] = ["Ringkasan Iklim Historis-bulanan"];
        $csv_data[] = ["Diunduh pada " . Carbon::now()->format("d F Y H:i")];
        $csv_data[] = ["Dibuat oleh SILENTERA-BMKG"];
        $csv_data[] = ["Lokasi: " . $lokasi];
        $csv_data[] = ["Bujur : " . $lon . utf8_decode("°BT")];
        $csv_data[] = ["Lintang: " . $zz . utf8_decode("°") . $arah];
        $csv_data[] = []; // An empty row for spacing
        $csv_data[] = ["Bulan", "Potensi Energi Surya", "Kecepatan Angin", "Kecepatan Angin Maksimum", "Arah Angin", "Suhu", "Suhu Maksimum", "Tutupan Awan Total", "Tutupan Awan Tinggi", "Tutupan Awan Menengah", "Tutupan Awan Rendah", "Waktu Terbit Matahari", "Waktu Terbenam Matahari", "Arah Matahari Zenith", "Curah Hujan", "Indeks Kebeningan"];
        $csv_data[] = ["","kWh/m2","m/s","m/s",utf8_decode("°"),utf8_decode("°C"),utf8_decode("°C"),"%","%","%","%","hh:mm:dd","hh:mm:dd",utf8_decode("°"),"mm",""];
        for ($idx = 0; $idx < count($bulan_list); $idx++) {
            $bulan = $bulan_list[$idx];
            $row_data = [$bulan];
            foreach ($index_names as $index_name) {
                try {
                    $response = Http::post($url, generateJsonPayload($index_name, $lat, $lon));
                    $response_json = $response->json();

                    if (in_array($index_name, ["potensi-bulanan", "kecepatan-angin-bulanan", "kecepatan-angin-maksimum-bulanan"])) {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$bulan];
                        $row_data[] = number_format($potensi_energi, 3, '.', '');
                    } elseif (in_array($index_name, ["sunrise-bulanan", "sunset-bulanan"])) {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$bulan];
                        $row_data[] = $potensi_energi;
                    } else {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$bulan];
                        $row_data[] = number_format(floatval($potensi_energi), 1, '.', '');
                    }
                } catch (\Exception $e) {
                    $row_data[] = "-";
                }
            }
            $csv_data[] = $row_data;
        }

        // Create a CSV buffer in memory
        $csv_buffer = fopen('php://temp', 'w');

        // Write the data to the CSV buffer
        foreach ($csv_data as $data) {
            fputcsv($csv_buffer, $data);
        }

        // Reset the pointer to the beginning of the file
        rewind($csv_buffer);

        // Create a response with the CSV content
        $response = Response::make(stream_get_contents($csv_buffer));
        $response->header('Content-Disposition', 'attachment; filename=Ringkasan_Iklim_Historis.csv');
        $response->header('Content-Type', 'text/csv');

        return $response;
    }

    public function historistahunanCsv(Request $request)
    {
        $host = env('HOST');

        $url = "http://" . $host . ":8080/api/search";
        $lokasi = $request->query('lokasi');
        $lon = $request->query('lon');
        $lat = $request->query('lat');
        if (floatval($lat) < 0) {
            $arah = "LS";
        } elseif (floatval($lat) > 0) {
            $arah = "LU";
        }
        // Memilih sheet aktif
        $zz = abs(floatval($lat));

        $index_names = [
            "potensi-tahunan",
            "kecepatan-angin-tahunan",
            "kecepatan-angin-maksimum-tahunan",
            "arah-angin-tahunan",
            "temperature-tahunan",
            "temperature-maximum-tahunan",
            "tutupan-awan-total-tahunan",
            "tutupan-awan-tinggi-tahunan",
            "tutupan-awan-menengah-tahunan",
            "tutupan-awan-rendah-tahunan",
            "sunrise-tahunan",
            "sunset-tahunan",
            "arah-matahari-zenith-tahunan",
            "curah-hujan-tahunan",
            "indeks-kebeningan-tahunan",
        ];

        function generateJsonPayload($name_index, $lat, $lon)
        {
            return [
                "distance" => "10km",
                "lat" => $lat,
                "lon" => $lon,
                "year" => 2023,
                "nameindex" => $name_index,
                "time" => "tahunan"
            ];
        }

        $tahun_list = ["1991", "1992", "1993", "1994", "1995", "1996", "1997", "1998", "1999", "2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017", "2018", "2019", "2020"];

        // Generate the CSV data
        $csv_data = [];
        $csv_data[] = ["Ringkasan Iklim Historis-tahunan"];
        $csv_data[] = ["Diunduh pada " . Carbon::now()->format("d F Y H:i")];
        $csv_data[] = ["Dibuat oleh SILENTERA-BMKG"];
        $csv_data[] = ["Lokasi: " . $lokasi];
        $csv_data[] = ["Bujur : " . $lon . utf8_decode("°BT")];
        $csv_data[] = ["Lintang: " . $zz . utf8_decode("°") . $arah];
        $csv_data[] = []; // An empty row for spacing
        $csv_data[] = ["Tahun", "Potensi Energi Surya", "Kecepatan Angin", "Kecepatan Angin Maksimum", "Arah Angin", "Suhu", "Suhu Maksimum", "Tutupan Awan Total", "Tutupan Awan Tinggi", "Tutupan Awan Menengah", "Tutupan Awan Rendah", "Waktu Terbit Matahari", "Waktu Terbenam Matahari", "Arah Matahari Zenith", "Curah Hujan", "Indeks Kebeningan"];
        $csv_data[] = ["","kWh/m2","m/s","m/s",utf8_decode("°"),utf8_decode("°C"),utf8_decode("°C"),"%","%","%","%","hh:mm:dd","hh:mm:dd",utf8_decode("°"),"mm",""];
        for ($idx = 0; $idx < count($tahun_list); $idx++) {
            $tahun = $tahun_list[$idx];
            $row_data = [$tahun];
            foreach ($index_names as $index_name) {
                try {
                    $response = Http::post($url, generateJsonPayload($index_name, $lat, $lon));
                    $response_json = $response->json();

                    if (in_array($index_name, ["potensi-tahunan", "kecepatan-angin-tahunan", "kecepatan-angin-maksimum-tahunan"])) {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$tahun];
                        $row_data[] = number_format($potensi_energi, 3, '.', '');
                    } elseif (in_array($index_name, ["sunrise-tahunan", "sunset-tahunan"])) {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$tahun];
                        $row_data[] = $potensi_energi;
                    } else {
                        $potensi_energi = $response_json["hits"]["hits"][0]["_source"][$tahun];
                        $row_data[] = number_format(floatval($potensi_energi), 1, '.', '');
                    }
                } catch (\Exception $e) {
                    $row_data[] = "-";
                }
            }
            $csv_data[] = $row_data;
        }

        // Create a CSV buffer in memory
        $csv_buffer = fopen('php://temp', 'w');

        // Write the data to the CSV buffer
        foreach ($csv_data as $data) {
            fputcsv($csv_buffer, $data);
        }

        // Reset the pointer to the beginning of the file
        rewind($csv_buffer);

        // Create a response with the CSV content
        $response = Response::make(stream_get_contents($csv_buffer));
        $response->header('Content-Disposition', 'attachment; filename=Ringkasan_Iklim_Historis.csv');
        $response->header('Content-Type', 'text/csv');

        return $response;
    }
    

}
