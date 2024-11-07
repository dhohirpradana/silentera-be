<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','register_admin','getimages']]);
    }

    public function login(Request $request)
    {
        try {
            
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');
    
            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            $profilimage = DB::table('profil_images')->where('user_id', $user->id)->get();
            $profil_image = $profilimage[0];
            $source_value = $profil_image->source;
            return response()->json([
                    'status' => 'success',
                    'user' => [
                        'uuid' => $user->uuid,
                        'name' => $user->name,
                        'email' => $user->email,
                        'status' => $user->status,
                        'email_verified_at' => $user->email_verified_at,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'taketour' => $user->taketour,
                    ],
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'profil_images' => $source_value
            ]);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }


    }

    function format_uuidv4($data)
    {
      assert(strlen($data) == 16);
    
      $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
      $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        
      return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function register(Request $request){
        function format_uuidv4($data)
        {
          assert(strlen($data) == 16);
        
          $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
          $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            
          return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
    
            $uuid = format_uuidv4(random_bytes(16));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'uuid' => $uuid,
                'password' => Hash::make($request->password),
                'taketour' => 1234,
                'status' => 'Authenticated'
            ]);

            $saveloc = ProfilImage::create([
                'user_id' => $user -> id,
                'source' => "null",
                'uuid' => $uuid,
            ]);
    
            
            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'userid' => ['email' => $user->email, 'name' => $user->name,'taketour' => $user->taketour],
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    public function register_admin(Request $request){
        function format_uuidv4($data)
        {
          assert(strlen($data) == 16);
        
          $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
          $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            
          return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
    
            $uuid = format_uuidv4(random_bytes(16));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'uuid' => $uuid,
                'password' => Hash::make($request->password),
                'taketour' => 1234,
                'status' => 'Admin'
            ]);
            
            $saveloc = ProfilImage::create([
                'user_id' => $user -> id,
                'source' => "null",
                'uuid' => $uuid,
            ]);
    
            
            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'userid' => ['email' => $user->email, 'name' => $user->name,],
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    public function editUser(Request $request)
    {
        try {
            $user = Auth::user();

            // $request->validate([
            //     'name' => 'string|max:255',
            //     'password' => 'string|min:6',
            // ]);

            if ($request->has('name')) {
                $user->name = $request->name;
            }


            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $user = Auth::user();

            $profilimage = DB::table('profil_images')->where('user_id', $user->id)->get();
            return response()->json([
                'status' => 'success',
                'user' => [
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'taketour' => $user->taketour,
                ],
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ],
                'profil_images' => $profilimage
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {

            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);

        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }

    public function me()
    {
        try {
            $user = Auth::user();
            return response()->json([
                'uuid' => $user->uuid,
                'status' => $user->status,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }

    }


    public function refresh()
    {
        try {
            $user = Auth::user();

            $profilimage = DB::table('profil_images')->where('user_id', $user->id)->get();
            return response()->json([
                'status' => 'success',
                'user' => [
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ],
                'profil_images' => $profilimage
            ]);
        } catch (Throwable $e) {
            report($e);
     
            return response()->json(['error' => $e-getMessage()],500);
        }
        
    }

    public function show($id)
    {
        $barang = User::findOrFail($id);
        return response()->json($barang);
    }

    public function indexadmin(Request $request)
    {
        try {
            $user = Auth::user();
        
            if ($user->status === 'Admin') {
                $tableusers = User::all();
        
                foreach ($tableusers as $item) {

        
                    $modifiedData[] = [
                        'uuid' => $item->uuid,
                        'name' => $item->name,
                        'email' => $item->email,
                        'status' => $item->status,
                        'email_verified_at' => $item->email_verified_at,
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

    public function taketour(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user) {
                $saveloc = DB::table('users')->where('id', $user->id)->update(['taketour' => $request->json('taketour')]);
                
                return response()->json([
                    'status' => 'success'
                ], 201);

            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (Throwable $e) {
            report($e);
         
            return response()->json();
        }


    }

    public function destroybyadmin(string $id)
    {
        try {

            $user = Auth::user();
            if ($user->status === 'Admin') {
                $user = DB::table('users')->where('uuid', $id)->first();
                $saveloc = DB::table('save_locs')->where('user_id', $user->id)->delete();
                $saveloc = DB::table('payments')->where('user_id', $user->id)->delete();
                $saveloc = DB::table('profil_images')->where('user_id', $user->id)->delete();
                $saveloc = DB::table('users')->where('uuid', $id)->delete();
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

    public function upload(Request $request)
    {   
        try {
            
            $user = Auth::user();
            if ($request->has('source')) {
                $source = $request->source;
                $response = ProfilImage::where('user_id', $user->id)
                ->update(['source' => $source]);

                return response()->json(['source' => $source], 200);
            }
            else{
                return response()->json(['error' => 'No image selected'], 400);
            }
            
            
        } catch (Throwable $e) {
            report($e);
    
            return response()->json(['error' => $e->getMessage()],500);
        }

    }

    public function getimages($filename)
    {
        // Construct the path to the uploaded image
        $filePath = 'images/' . $filename;

        if (Storage::disk('public')->exists($filePath)) {
            // Read the content of the file
            $fileContent = Storage::disk('public')->get($filePath);

            // You can then send the content as a response, for example as a download
            return response($fileContent, 200)
                ->header('Content-Type', 'image/jpeg'); // Adjust the content type accordingly
        }

        return response()->json(['error' => 'File not found'], 404);
    }

}
