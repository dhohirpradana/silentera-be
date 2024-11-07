<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Saveloc;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SavelocController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }



    public function index()
    {
        try {
            $user = Auth::user();
            $saveloc = DB::table('save_locs')->where('user_id', $user->id)->get();
    
            // Mapping of provinces to their corresponding base values
            $provinceBaseMapping = [
                'ACEH', 'SUMATERA UTARA', 'SUMATERA BARAT', 'RIAU', 'KEPULAUAN RIAU', 'JAMBI',
                'BENGKULU', 'SUMATERA SELATAN', 'KEPULAUAN BANGKA BELITUNG', 'LAMPUNG', 'BANTEN',
                'DKI JAKARTA', 'JAWA BARAT', 'JAWA TENGAH', 'DAERAH ISTIMEWA YOGYAKARTA', 'JAWA TIMUR', 'BALI',
                'NUSA TENGGARA BARAT', 'NUSA TENGGARA TIMUR', 'KALIMANTAN BARAT', 'KALIMANTAN TENGAH',
                'KALIMANTAN SELATAN', 'KALIMANTAN TIMUR', 'KALIMANTAN UTARA', 'SULAWESI UTARA',
                'GORONTALO', 'SULAWESI TENGAH', 'SULAWESI BARAT', 'SULAWESI SELATAN',
                'SULAWESI TENGGARA', 'MALUKU', 'MALUKU UTARA', 'PAPUA BARAT', 'PAPUA',
            ];
    
            $baseValue = [7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 8, 8, 8, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 9, 9, 9, 9];
    
            $modifiedData = [];
    
            foreach ($saveloc as $item) {
                $province = strtoupper($item->province);
                $lat = 0; // Default value if the province is not found in the conditions
    
                $index = array_search($province, $provinceBaseMapping);
    
                if ($index !== false) {
                    $utc = $baseValue[$index];
                }
    
                $modifiedData[] = [
                    'id' => $item->uuid,
                    'utc' => $utc,
                    'lat' => (float) $item->lat,
                    'lon' => (float) $item->lon,
                    'province' => $item->province,
                    'region' => $item->region,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            }
    
            return response()->json($modifiedData);
        } catch (Throwable $e) {
            report($e);
    
            return response()->json();
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        function format_uuidv4($data)
        {
          assert(strlen($data) == 16);
        
          $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
          $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            
          return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        try {

            $request->validate([
                'lat' => 'required|numeric',
                'lon' => 'required|numeric', 
                'province' => 'required|string',
                'region' => 'required|string',
            ]);
    
            $user = Auth::user();
            $uuid = format_uuidv4(random_bytes(16));
    
            $saveloc = SaveLoc::create([
                'lat' => $request->json('lat'),
                'lon' => $request->json('lon'),
                'province' => $request->json('province'),
                'region' => $request->json('region'),
                'user_id' => $user -> id,
                'uuid' => $uuid,
            ]);
    
            return response()->json($saveloc, 201);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
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
        try {
            $user = Auth::user();

            $saveloc = DB::table('save_locs')->where('uuid', $id)->where('user_id', $user -> id)->get();
    
            $modifiedData = []; // Contoh: Mengubah $saveloc menjadi array asosiatif
            foreach ($saveloc as $item) {
                $modifiedData[] = [
                    'id' => $item->uuid,
                    'lat' => (float) $item->lat,
                    'lon' => (float) $item->lon,
                    'province' => $item->province,
                    'region' => $item->region,
                    'created_at'=> $item->created_at,
                    'updated_at'=> $item->updated_at,
                ];
            }
    
            return response()->json($modifiedData);
        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $request->validate([
                'lat' => 'required|numeric',
                'lon' => 'required|numeric', 
                'province' => 'required|string',
                'region' => 'required|string',
            ]);
    
            $user = Auth::user();
    
            $saveloc = DB::table('save_locs')
                ->where('uuid', $id)
                ->where('user_id', $user -> id)
                ->update([
                    'lat' => $request->json('lat'),
                    'lon'=>$request->json('lon'),
                    'province'=>$request->json('province'),
                    'region'=>$request->json('region')]);
    
            return response()->json($saveloc, 200);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $user = Auth::user();

            $saveloc = DB::table('save_locs')->where('uuid', $id)->where('user_id', $user -> id)->delete();
            return response()->json([
                'status' => 'success',
                'id_delete' => $id
            ], 201);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }
}
