<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GetlistindexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index_prakiraan()
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {
                $url = "http://127.0.0.1:5601/api/index_management/indices";
                $response = Http::get($url);
            
                $data = $response->json();
            
                $result = [];
                foreach ($data as $item) {
                    if (strpos($item['name'], 'prakiraan') !== false) {
                        $name = $item['name'];
                        $size = $item['size'];
                        $health = $item['health'];
                        $documents = $item['documents'];
                        if ($documents >= 140000 || ($documents == 15442 || $documents == 26080)) {
                            // Kondisi akan terpenuhi jika $documents lebih besar atau sama dengan 140,000
                            // atau jika $documents sama dengan 15442 atau 26080
                            $status = 'Success';
                        } else {
                            // Kondisi akan terpenuhi jika kondisi di atas tidak terpenuhi
                            $status = 'Pending';
                        }
                        $tanggal = explode('-', $name)[1];
                        $bulan = explode('-', $name)[2];
                        $tahun = explode('-', $name)[3];
                        $typename = explode('-', $name)[4];
                        $jenis = explode('-', $name)[0];
                        $typename = str_replace(" ", "-", $typename);
                        $result[] = [
                            'typename' => $typename,
                            'jenis' => $jenis,
                            'size' => $size,
                            'health' => $health,
                            'status' => $status,
                            'tahun' => $tahun,
                            'bulan' => $bulan,
                            'tanggal' => $tanggal,
                        ];
                    }
                }
            
                return $result;

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
        }


    }

    public function index_historis_bulanan()
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {
                $url = "http://127.0.0.1:5601/api/index_management/indices";
                $response = Http::get($url);
            
                $data = $response->json();
            
                $result = [];
                foreach ($data as $item) {
                    if (strpos($item['name'], 'historis-bulanan') !== false) {
                        $name = $item['name'];
                        $size = $item['size'];
                        $health = $item['health'];

                        $documents = $item['documents'];
                        if ($documents >= 140000 || ($documents == 15442 || $documents == 26080)) {
                            // Kondisi akan terpenuhi jika $documents lebih besar atau sama dengan 140,000
                            // atau jika $documents sama dengan 15442 atau 26080
                            $status = 'Success';
                        } else {
                            // Kondisi akan terpenuhi jika kondisi di atas tidak terpenuhi
                            $status = 'Pending';
                        }
                        $typename = implode(' ', array_slice(explode('-', $name), 3));
                        $typename = str_replace(" ", "-", $typename);
                        $result[] = [
                            'typename' => $typename,
                            'size' => $size,
                            'health' => $health,
                            'status' => $status,
                        ];
                    }
                }
            
                return $result;

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
        }


    }


    public function index_historis_tahunan()
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {
                $url = "http://127.0.0.1:5601/api/index_management/indices";
                $response = Http::get($url);
            
                $data = $response->json();
            
                $result = [];
                foreach ($data as $item) {
                    if (strpos($item['name'], 'historis-tahunan') !== false) {
                        $name = $item['name'];
                        $size = $item['size'];
                        $health = $item['health'];
                        $documents = $item['documents'];
                        if ($documents >= 140000 || ($documents == 15442 || $documents == 26080)) {
                            // Kondisi akan terpenuhi jika $documents lebih besar atau sama dengan 140,000
                            // atau jika $documents sama dengan 15442 atau 26080
                            $status = 'Success';
                        } else {
                            // Kondisi akan terpenuhi jika kondisi di atas tidak terpenuhi
                            $status = 'Pending';
                        }
                        $typename = implode(' ', array_slice(explode('-', $name), 3));
                        $typename = str_replace(" ", "-", $typename);
                        $result[] = [
                            'typename' => $typename,
                            'size' => $size,
                            'health' => $health,
                            'status' => $status,
                        ];
                    }
                }
            
                return $result;

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
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
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->status === 'Admin') {
                $host = env('HOST');
                $request_data = $request->json()->all();
                $bulan = $request_data['bulan'];
                $tahun = $request_data['tahun'];
                $jenis = $request_data['jenis'];
                $typename = $request_data['typename'];
                $tanggal = $request_data['tanggal'];
                
                if ($jenis == 'historis-bulanan' || $jenis == 'historis-tahunan') {
                    $tahun = 2023;
                    $indexname = $jenis . '-' . $tahun . '-' . $typename;
                } elseif ($jenis == 'prakiraan') {
                    $indexname = $jenis . '-' . $tanggal . '-' . $bulan . '-' . $tahun . '-' . $typename;
                }
                
                $payload = ["indices" => [$indexname]];
                $url = "http://" . $host . ":5601/api/index_management/indices/delete";
                $headers = [
                    "Content-Type" => "application/json",
                    "Accept" => "*/*",
                    "Accept-Encoding" => "gzip, deflate",
                    "Accept-Language" => "en-US,en;q=0.9",
                    "Connection" => "keep-alive",
                    "Host" =>  $host . ":5601",
                    "Origin" => "http://" . $host . ":5601",
                    "Referer" => "http://" . $host . "/app/home",
                    "kbn-xsrf" => "true",
                    "X-Kbn-Context" => "%7B%22type%22%3A%22application%22%2C%22name%22%3A%22home%22%2C%22url%22%3A%22%2Fapp%2Fhome%22%7D"
                ];
            
                $response = Http::withHeaders($headers)->post($url, $payload);
                $statusCode = $response->status();
                if ($statusCode === 200) {
                    return response()->json(['status' => 'SUCCESS']);
                }
                return $response->json();
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
        }
    }

}
