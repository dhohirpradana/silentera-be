<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {
                $payments = Payments::all();
        
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
                    $modifiedData[] = [
                        'id' => $item->uuid,
                        'lat' => (float) $item->lat,
                        'lon' => (float) $item->lon,
                        'province' => $item->province,
                        'region' => $item->region,
                        'status' => $status,
		        'email' => $user->email,
                        'name' => $user->name,
                        'paket' => $paket+1,
                        'timetopayment' => $daysDiff,
                        'exp' => $item->lastday,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }
        
                return response()->json($modifiedData);

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
        }


    }

    public function indexuser()
    {
        try {
            $user = Auth::user();
            $payments = DB::table('payments')->where('user_id', $user -> id)->get();
            $modifiedData = []; // Contoh: Mengubah $saveloc menjadi array asosiatif
            foreach ($payments as $item) {
                $status = $item->status;
    
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
                $province = strtoupper($item->province);
    
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
    
                $index = array_search($province, $provinceBaseMapping);
    
                if ($index !== false) {
                    $utc = $baseValue[$index];
                }
                $expDate = Carbon::parse($item->lastday);
                $createdAt = Carbon::parse($item->created_at);
                $paket = $createdAt->diffInDays($expDate);
                $modifiedData[] = [
                    'id' => $item->uuid,
                    'utc' => $utc,
                    'lat' => (float) $item->lat,
                    'lon' => (float) $item->lon,
                    'province' => $item->province,
                    'region' => $item->region,
                    'paket' => $paket+1,
                    'status' => $status,
                    'timetopayment' => $daysDiff,
                    'exp' => $item->lastday,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
                'day' => 'required|numeric',
            ]);
    
            $day = $request->json('day');
    
            $currentDate = Carbon::now();
            $fourDaysAhead = $currentDate->addDays($day)->toDateString();
    
            $user = Auth::user();
            $uuid = format_uuidv4(random_bytes(16));
    
            $payments = Payments::create([
                'lat' => $request->json('lat'),
                'lon' => $request->json('lon'),
                'province' => $request->json('province'),
                'region' => $request->json('region'),
                'user_id' => $user->id,
                'uuid' => $uuid,
                'lastday' => $fourDaysAhead,
                'status' => "Pending",
            ]);
    
            return response()->json($payments, 201);
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        try {

            $request->validate([
                'status' => 'required|string',
            ]);
    
            $user = Auth::user();
    
            $saveloc = DB::table('payments')
                ->where('uuid', $id)
                ->update([
                    'status'=>$request->json('status')]);
                
            return response()->json(['status' => 'success'], 201);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroybyuser(string $id)
    {
        try {

            $user = Auth::user();

            $saveloc = DB::table('payments')->where('uuid', $id)->where('user_id', $user -> id)->delete();
            return response()->json([
                'status' => 'success',
                'id_delete' => $id
            ], 201);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    public function destroybyadmin(string $id)
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {

            $saveloc = DB::table('payments')->where('uuid', $id)->delete();
            return response()->json([
                'status' => 'success',
                'id_delete' => $id
            ], 201);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }
}
