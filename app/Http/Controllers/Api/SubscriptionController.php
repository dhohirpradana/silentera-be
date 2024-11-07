<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Location;
use Carbon\Carbon;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::with('plan', 'location')->get();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'plan_id' => 'required|exists:plans,id',
                'location' => 'required|array', // Expect location data as an array
                'location.region' => 'required|string|max:100',
                'location.province' => 'required|string|max:100',
                'location.lon' => 'required|numeric',
                'location.lat' => 'required|numeric',
                'start_date' => 'required|date',
                'package' => 'required|string|max:100',
                'qty' => 'required|numeric|min:1',
                'base64image' => 'required|string',
            ]);

            if ($validatedData->fails()) {
                throw new ValidationException($validatedData);
            }

            $validatedData = $validatedData->validated();


            $base64String = $request->input('base64image');

            list($mimeInfo, $imageData) = explode(',', $base64String);

            $extension = '';
            if (preg_match('/^data:image\/(.*);base64$/', $mimeInfo, $matches)) {
                $extension = $matches[1];
            } else {
                return response()->json([
                    'message' => 'Invalid image format. Please provide a valid Base64-encoded image with the correct MIME type.',
                ], 422);
            }

            $imagePath = null;
            if ($request->filled('base64image')) {
                $imageName = uniqid() . '.' . $extension;
                $imagePath = 'subscriptions/' . $imageName;
                Storage::disk('public')->put($imagePath, base64_decode($imageData));
            }

            // Extract location data
            $locationData = $validatedData['location'];

            // Create or find the location
            $location = Location::firstOrCreate(
                [
                    'region' => $locationData['region'],
                    'province' => $locationData['province'],
                    'lon' => $locationData['lon'],
                    'lat' => $locationData['lat'],
                ],
                $locationData
            );

            $qty = $validatedData['qty'] ?? 1;
            $plan = Plan::findOrFail($validatedData['plan_id']);
            $price = match ($validatedData['package']) {
                'weekly' => $plan->price_weekly,
                'monthly' => $plan->price_monthly,
                'annual' => $plan->price_annual,
                default => 0,
            };
            $grandPrice = $price * $qty;

            $startDate = Carbon::parse($validatedData['start_date']);
            if ($validatedData['package'] === 'monthly') {
                $endDate = $startDate->copy()->addMonths($qty);
            } else if ($validatedData['package'] === 'annual') {
                $endDate = $startDate->copy()->addYears($qty);
            } else if ($validatedData['package'] === 'weekly') {
                $endDate = $startDate->copy()->addWeeks($qty);
            } else {
                return response()->json("Invalid package!", 422);
            }

            $subscription = Subscription::create([
                'user_id' => $validatedData['user_id'],
                'plan_id' => $validatedData['plan_id'],
                'location_id' => $location->id,
                'start_date' => $validatedData['start_date'],
                'package' => $validatedData['package'],
                'qty' => $qty,
                'end_date' => $endDate,
                'price' => $price,
                'grand_price' => $grandPrice,
                'image_path' => $imagePath,
            ]);

            return response()->json([
                'subscription' => $subscription,
                'image_url' => $subscription->image_path ? Storage::url($subscription->image_path) : null,
            ], 201);
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json([
                // 'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function show($id)
    {
        return Subscription::with('plan', 'location')->findOrFail($id);
    }

    public function listByUserId($userId)
    {
        // Retrieve all subscriptions for the given user ID
        $subscriptions = Subscription::where('user_id', $userId)->with('plan', 'location')->get();

        return response()->json($subscriptions, 200);
    }

    // public function update(Request $request, $id)
    // {
    //     $subscription = Subscription::findOrFail($id);
    //     $subscription->update($request->only(['user_id', 'plan_id', 'location_id', 'start_date', 'end_date', 'price']));

    //     return $subscription;
    // }

    // public function destroy($id)
    // {
    //     $subscription = Subscription::findOrFail($id);
    //     $subscription->delete();

    //     return response()->json(['message' => 'Subscription deleted successfully']);
    // }
}
