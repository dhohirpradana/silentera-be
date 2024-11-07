<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Facades\Response;

class ElasticsearchController extends Controller
{

    public function index(Request $request)
    {
        $host = "10.1.111.141";
        $distance = $request->json('distance');
        $lat = $request->json('lat');
        $lon = $request->json('lon');
        $nameindex = $request->json('nameindex');
        $month = $request->json('month');
        $year = $request->json('year');
        $day = $request->json('day');
        $datetime = $day . '-' . $month . '-' . $year;
        $datetimet = "03-01-2023";
        $url = 'http://' . $host . ':31299/prakiraan-' . $datetimet . '-' . $nameindex . '/_search';
        $jsons = [
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "geo_distance" => [
                                "distance" => $distance,
                                "location" => [
                                    "lat" => $lat,
                                    "lon" => $lon,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::post($url, $jsons);
        $response_json = $response->json();

        // Data tanggal dan respons
        $date_str = "08-01-2023";
        $converted_date_str = str_replace('-', '/', $datetime);
        // Ubah start_date dan end_date menjadi objek Carbon
        $start_date_obj = Carbon::createFromFormat("d/m/Y", $converted_date_str);
        $eleven_days_after_start_date = $start_date_obj->copy()->addDays(14);
        $end_date = $eleven_days_after_start_date->format("d/m/Y");
        $end_date_obj = Carbon::createFromFormat("d/m/Y", $end_date);

        // Mendapatkan tanggal 5 hari setelah start_date
        $five_days_after_start_date = $start_date_obj->copy()->addDays(5);

        // Ubah menjadi string dengan format yang diinginkan (misalnya "%d/%m/%Y")
        $five_days_after_start_date_str = $five_days_after_start_date->format("d/m/Y");

        $angka = "001";
        $respons_list = [];
        
        for ($i = 0; $i < 5; $i++) {
            $current_date = $start_date_obj->copy()->addDays($i);
        
            try {
                for ($j = 0; $j < 24; $j++) {
                    $key = $current_date->format("d/m/Y");
                    $data = $response_json["hits"]["hits"][0]["_source"][$angka];
                    $elemen_respons = [
                        "date" => $key,
                        "jam" => (string)$j,
                        "value" => $data,
                    ];
                    $respons_list[] = $elemen_respons;
                    $angka = str_pad((int)$angka + 1, 3, "0", STR_PAD_LEFT);
                }
            } catch (Exception $e) {
                // echo $e;
                continue;
            }
        }
        
        // Menduplikasi data dari 5 hari pertama menjadi 35 hari
        $original_respons_count = count($respons_list);
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < $original_respons_count; $j++) {
                $respons_list[] = $respons_list[$j];
            }
        }
        
        // Mengganti tanggal setiap 24 data
        $start_date = "01/01/2023";
        for ($i = 0; $i < count($respons_list); $i++) {
            $current_date_obj = $start_date_obj->copy()->addDays(floor($i / 24)); // Menghitung tanggal berdasarkan 24 jam per hari
            $current_date_str = $current_date_obj->format("d/m/Y");
            
            $respons_list[$i]["date"] = $current_date_str;
        }
        
        return response()->json($respons_list);
    }

    public function search(Request $request)
    {
        try {
            $host = env('HOST');
            $distance = $request->json('distance');
            $lat = $request->json('lat');
            $lon = $request->json('lon');
            $year = $request->json('year');
            $nameindex = $request->json('nameindex');
            $time = $request->json('time');
            
            $response = Http::post('http://' . $host . ':31299/historis-' . $time . '-' . $year . '-' . $nameindex . '/_search', [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'geo_distance' => [
                                    'distance' => $distance,
                                    'location' => [
                                        'lat' => $lat,
                                        'lon' => $lon
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $newtime = $time;
                if ($time == "bulanan") {
                    if ((!isset($responseData['hits']['hits'][0]['_source']['Jul']) || $responseData['hits']['hits'][0]['_source']['Jul'] == 0) && (!isset($responseData['hits']['hits'][0]['_source']['Jun']) || $responseData['hits']['hits'][0]['_source']['Jun'] == 0)) {
                        
                        $initialLat = $lat; // Latitude awal
                        $initialLon = $lon; // Longitude awal
                        $numCheck = 1;

                        $newLat = $initialLat; // Latitude baru
                        $newLon = $initialLon; // Longitude baru
                        $isFound = false;

                        while (!$isFound) {
                            // Perbarui koordinat latitude atau longitude dengan menambah atau mengurangi 0.1
                            if ($numCheck == 1) {
                                $newLat += 0.1;
                            } elseif ($numCheck == 2) {
                                $newLat -= 0.1;
                            } elseif ($numCheck == 3) {
                                $newLon += 0.1;
                            } else {
                                $newLon -= 0.1;
                            }
                            
                            $numCheck++;

                            // Lakukan pencarian ke lokasi baru dengan koordinat yang telah diperbarui
                            $newResponse = Http::post('http://' . $host . ':31299/historis-' . $time . '-' . $year . '-' . $nameindex . '/_search', [
                                'query' => [
                                    'bool' => [
                                        'must' => [
                                            [
                                                'geo_distance' => [
                                                    'distance' => $distance,
                                                    'location' => [
                                                        'lat' => $newLat,
                                                        'lon' => $newLon
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]);
                            if ($newResponse->successful()) {
                                $newData = $newResponse->json();
                                // $jul = $newData['hits']['hits'][0]['_source']['Jul'];
                                // $oct = $newData['hits']['hits'][0]['_source']['Jun'];

                                if (isset($newData['hits']['hits'][0]['_source']['Jul']) && $newData['hits']['hits'][0]['_source']['Jul'] != 0 || isset($newData['hits']['hits'][0]['_source']['Jun']) && $newData['hits']['hits'][0]['_source']['Jun'] != 0) {
                                    // Nilai "Jul" atau "Oct" bukan 0, temukan lokasi yang valid
                                    $isFound = true;
                                }
                                
                            } else {
                                return response()->json(['message' => "error response server"], 500);
                            }
                        }

                        return $newData; // Kembalikan data hasil pencarian di lokasi baru
                    } else {
                        return $responseData; // Kembalikan respons asli jika nilai "Jul" dan "Oct" bukan 0
                    }
                } else {
                    if ((!isset($responseData['hits']['hits'][0]['_source']['2012']) || $responseData['hits']['hits'][0]['_source']['2012'] == 0) && (!isset($responseData['hits']['hits'][0]['_source']['2011']) || $responseData['hits']['hits'][0]['_source']['2011'] == 0)) {
                        
                        $initialLat = $lat; // Latitude awal
                        $initialLon = $lon; // Longitude awal
                        $numCheck = 1;

                        $newLat = $initialLat; // Latitude baru
                        $newLon = $initialLon; // Longitude baru
                        $isFound = false;

                        while (!$isFound) {
                            // Perbarui koordinat latitude atau longitude dengan menambah atau mengurangi 0.1
                            if ($numCheck == 1) {
                                $newLat += 0.1;
                            } elseif ($numCheck == 2) {
                                $newLat -= 0.1;
                            } elseif ($numCheck == 3) {
                                $newLon += 0.1;
                            } else {
                                $newLon -= 0.1;
                            }
                            
                            $numCheck++;

                            // Lakukan pencarian ke lokasi baru dengan koordinat yang telah diperbarui
                            $newResponse = Http::post('http://' . $host . ':31299/historis-' . $time . '-' . $year . '-' . $nameindex . '/_search', [
                                'query' => [
                                    'bool' => [
                                        'must' => [
                                            [
                                                'geo_distance' => [
                                                    'distance' => $distance,
                                                    'location' => [
                                                        'lat' => $newLat,
                                                        'lon' => $newLon
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]);

                            if ($newResponse->successful()) {
                                $newData = $newResponse->json();
                                // $jul = $newData['hits']['hits'][0]['_source']['Jul'];
                                // $oct = $newData['hits']['hits'][0]['_source']['Jun'];

                                if (isset($newData['hits']['hits'][0]['_source']['2012']) && $newData['hits']['hits'][0]['_source']['2012'] != 0 || isset($newData['hits']['hits'][0]['_source']['2011']) && $newData['hits']['hits'][0]['_source']['2011'] != 0) {
                                    // Nilai "Jul" atau "Oct" bukan 0, temukan lokasi yang valid
                                    $isFound = true;
                                }
                                
                            } else {
                                return response()->json(['message' => "error response server"], 500);
                            }
                        }

                        return $newData; // Kembalikan data hasil pencarian di lokasi baru
                    } else {
                        return $responseData; // Kembalikan respons asli jika nilai "Jul" dan "Oct" bukan 0
                    }
                }
            } else {
                return response()->json(['message' => "error response server"], 500);
            }
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function prakiraan(Request $request)
    {
        try {
            $host = env('HOST');
            $distance = $request->json('distance');
            $lat = $request->json('lat');
            $lon = $request->json('lon');
            $nameindex = $request->json('nameindex');
            $time = $request->json('time');
            $datetime = $request->json('datetime');
            $response = Http::post('http://' . $host . ':31299/prakiraan-' . $datetime . '-' . $nameindex . '/_search', [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'geo_distance' => [
                                    'distance' => $distance,
                                    'location' => [
                                        'lat' => $lat,
                                        'lon' => $lon
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    
            if ($response->successful()) {
                $responseData = $response->json();
                $newtime = $time;
                if ($time == "bulanan") {
                    if ((!isset($responseData['hits']['hits'][0]['_source']['bulan-1']) || $responseData['hits']['hits'][0]['_source']['bulan-1'] == 0) && (!isset($responseData['hits']['hits'][0]['_source']['bulan-1']) || $responseData['hits']['hits'][0]['_source']['bulan-1'] == 0)) {
                        
                        $initialLat = $lat; // Latitude awal
                        $initialLon = $lon; // Longitude awal
                        $numCheck = 1;

                        $newLat = $initialLat; // Latitude baru
                        $newLon = $initialLon; // Longitude baru
                        $isFound = false;

                        while (!$isFound) {
                            // Perbarui koordinat latitude atau longitude dengan menambah atau mengurangi 0.1
                            if ($numCheck == 1) {
                                $newLat += 0.1;
                            } elseif ($numCheck == 2) {
                                $newLat -= 0.1;
                            } elseif ($numCheck == 3) {
                                $newLon += 0.1;
                            } else {
                                $newLon -= 0.1;
                            }
                            
                            $numCheck++;

                            // Lakukan pencarian ke lokasi baru dengan koordinat yang telah diperbarui
                            $newResponse = Http::post('http://' . $host . ':31299/prakiraan-' . $datetime . '-' . $nameindex . '/_search', [
                                'query' => [
                                    'bool' => [
                                        'must' => [
                                            [
                                                'geo_distance' => [
                                                    'distance' => $distance,
                                                    'location' => [
                                                        'lat' => $newLat,
                                                        'lon' => $newLon
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]);

                            if ($newResponse->successful()) {
                                $newData = $newResponse->json();
                                // $jul = $newData['hits']['hits'][0]['_source']['Jul'];
                                // $oct = $newData['hits']['hits'][0]['_source']['Jun'];

                                if (isset($newData['hits']['hits'][0]['_source']['bulan-1']) && $newData['hits']['hits'][0]['_source']['bulan-1'] != 0 || isset($newData['hits']['hits'][0]['_source']['bulan-1']) && $newData['hits']['hits'][0]['_source']['bulan-1'] != 0) {
                                    // Nilai "Jul" atau "Oct" bukan 0, temukan lokasi yang valid
                                    $isFound = true;
                                }
                                
                            } else {
                                $newResponse = Http::post('http://' . $host . ':31299/prakiraan-' . $datetime . '-' . $nameindex . '/_search', [
                                    'query' => [
                                        'bool' => [
                                            'must' => [
                                                [
                                                    'geo_distance' => [
                                                        'distance' => $distance,
                                                        'location' => [
                                                            'lat' => $newLat,
                                                            'lon' => $newLon
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]);
                                $newData = $newResponse->json();
                                return $newData;
                            }
                        }

                        return $newData; // Kembalikan data hasil pencarian di lokasi baru
                    } else {
                        return $responseData; // Kembalikan respons asli jika nilai "Jul" dan "Oct" bukan 0
                    }
                } else {
                    return $responseData; 
                }
            } else {
                return response()->json(['message' => "error response server"]);
            }
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function new_prakiraan(Request $request)
    {
        $host = env('HOST');
        $distance = $request->json('distance');
        $lat = $request->json('lat');
        $lon = $request->json('lon');
        $nameindex = $request->json('nameindex');
        $time = $request->json('time');
        $datetime = $request->json('datetime');
        $datetimet = "03-01-2023";

        $url = 'http://' . $host . ':31299/prakiraan-' . $datetimet . '-' . $nameindex . '/_search';
        $jsons = [
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "geo_distance" => [
                                "distance" => $distance,
                                "location" => [
                                    "lat" => $lat,
                                    "lon" => $lon,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::post($url, $jsons);
        $response_json = $response->json();

        // Data tanggal dan respons
        $start_date = str_replace('-', '/', $datetime);

        // Ubah start_date dan end_date menjadi objek Carbon
        $start_date_obj = Carbon::createFromFormat("d/m/Y", $start_date);
        $eleven_days_after_start_date = $start_date_obj->copy()->addDays(14);
        $end_date = $eleven_days_after_start_date->format("d/m/Y");
        $end_date_obj = Carbon::createFromFormat("d/m/Y", $end_date);

        // Mendapatkan tanggal 5 hari setelah start_date
        $five_days_after_start_date = $start_date_obj->copy()->addDays(5);

        // Ubah menjadi string dengan format yang diinginkan (misalnya "%d/%m/%Y")
        $five_days_after_start_date_str = $five_days_after_start_date->format("d/m/Y");

        $angka = "001";
        $respons_list = [];

        for ($i = 0; $i <= $end_date_obj->diffInDays($start_date_obj); $i++) {
            $current_date = $start_date_obj->copy()->addDays($i);

            if ($five_days_after_start_date_str == $current_date->format("d/m/Y")) {
                $angka = str_pad((int)$angka + 2, 3, "0", STR_PAD_LEFT);
            }

            try {
                if ((int)$angka > 119) {
                    for ($j = 0; $j < 24; $j += 3) {
                        $key = $current_date->format("d/m/Y");
                        $data = $response_json["hits"]["hits"][0]["_source"][$angka];
                        $elemen_respons = [
                            "date" => $key,
                            "jam" => (string)$j,
                            "value" => $data,
                        ];
                        $respons_list[] = $elemen_respons;
                        $angka = str_pad((int)$angka + 3, 3, "0", STR_PAD_LEFT);
                    }
                } else {
                    for ($j = 0; $j < 24; $j++) {
                        $key = $current_date->format("d/m/Y");
                        $data = $response_json["hits"]["hits"][0]["_source"][$angka];
                        $elemen_respons = [
                            "date" => $key,
                            "jam" => (string)$j,
                            "value" => $data,
                        ];
                        $respons_list[] = $elemen_respons;
                        $angka = str_pad((int)$angka + 1, 3, "0", STR_PAD_LEFT);
                    }
                }
            } catch (Exception $e) {
                // echo $e;
                continue;
            }
        }

        return response()->json($respons_list);
    }

    public function analysis_prakiraan(Request $request)
    {
        $host = "43.243.142.245";
        $distance = $request->json('distance');
        $lat = $request->json('lat');
        $lon = $request->json('lon');
        $month = $request->json('month');
        $day = $request->json('day');
        $year = $request->json('year');
        $url = 'http://' . $host . ':31299/analisis-bulanan/_search';
        $jsons = [
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "geo_distance" => [
                                "distance" => $distance,
                                "location" => [
                                    "lat" => -4,
                                    "lon" => 132.9,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::post($url, $jsons);
        $response_json = $response->json();
        $datetime = $day . '-' . $mount . '-' . $year;
        // Data tanggal dan respons
        $start_date = str_replace('-', '/', $datetime);

        // Ubah start_date dan end_date menjadi objek Carbon
        $start_date_obj = Carbon::createFromFormat("d/m/Y", $start_date);
        $eleven_days_after_start_date = $start_date_obj->copy()->addDays(14);
        $end_date = $eleven_days_after_start_date->format("d/m/Y");
        $end_date_obj = Carbon::createFromFormat("d/m/Y", $end_date);

        // Mendapatkan tanggal 5 hari setelah start_date
        $five_days_after_start_date = $start_date_obj->copy()->addDays(5);

        // Ubah menjadi string dengan format yang diinginkan (misalnya "%d/%m/%Y")
        $five_days_after_start_date_str = $five_days_after_start_date->format("d/m/Y");

        $angka = "001";
        $respons_list = [];

        for ($i = 0; $i <= $end_date_obj->diffInDays($start_date_obj); $i++) {
            $current_date = $start_date_obj->copy()->addDays($i);

            try {
                for ($j = 0; $j < 24; $j++) {
                    $key = $current_date->format("d/m/Y");
                    $data = $response_json["hits"]["hits"][0]["_source"][$angka];
                    $elemen_respons = [
                        "date" => $key,
                        "jam" => (string)$j,
                        "value" => $data,
                    ];
                    $respons_list[] = $elemen_respons;
                    $angka = str_pad((int)$angka + 1, 3, "0", STR_PAD_LEFT);
                }
            } catch (Exception $e) {
                // echo $e;
                continue;
            }
        }
        $datetime =  $day . '-' . $mount . '-' . $year;
        // Data tanggal dan respons
        $start_date = str_replace('-', '/', $datetime);

        // Ubah start_date dan end_date menjadi objek Carbon
        $start_date_obj = Carbon::createFromFormat("d/m/Y", $start_date);
        $eleven_days_after_start_date = $start_date_obj->copy()->addDays(30);
        $end_date = $eleven_days_after_start_date->format("d/m/Y");
        $end_date_obj = Carbon::createFromFormat("d/m/Y", $end_date);

        // Mendapatkan tanggal 5 hari setelah start_date
        $five_days_after_start_date = $start_date_obj->copy()->addDays(5);

        // Ubah menjadi string dengan format yang diinginkan (misalnya "%d/%m/%Y")
        $five_days_after_start_date_str = $five_days_after_start_date->format("d/m/Y");

        $angka = "001";
        $respons_list = [];

        for ($i = 0; $i <= $end_date_obj->diffInDays($start_date_obj); $i++) {
            $current_date = $start_date_obj->copy()->addDays($i);

            try {
                for ($j = 0; $j < 24; $j++) {
                    $key = $current_date->format("d/m/Y");
                    $data = $response_json["hits"]["hits"][0]["_source"][$angka];
                    $elemen_respons = [
                        "date" => $key,
                        "jam" => (string)$j,
                        "value" => $data,
                    ];
                    $respons_list[] = $elemen_respons;
                    $angka = str_pad((int)$angka + 1, 3, "0", STR_PAD_LEFT);
                }
            } catch (Exception $e) {
                // echo $e;
                continue;
            }
        }

        return response()->json($respons_list);
    }

    public function windrose(Request $request,string $month)
    {
        try {
            $host = env('HOST');
            $distance = $request->json('distance');
            $lat = $request->json('lat');
            $lon = $request->json('lon');
            $response = Http::post('http://' . $host . ':31299/' . $month . '/_search', [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'geo_distance' => [
                                    'distance' => $distance,
                                    'location' => [
                                        'lat' => $lat,
                                        'lon' => $lon
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            if ($response->successful()) {
                $data = $response->json();
                return $data;
            } else {
                return response()->json(['message' => "error response server"]);
            }
        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }
}
