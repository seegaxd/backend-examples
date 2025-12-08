<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service' => 'required|string',
            'topic' => 'required|string',
            'payload' => 'required|array', // JSON объект
            'expired_at' => 'nullable|date',
            'subscriber_id' => 'required|exists:subscribers,id',
        ]);

        return Subscription::create($validated);
    }

    public function show($id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        return $subscription;
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $validated = $request->validate([
            'service' => 'string',
            'topic' => 'string',
            'payload' => 'array',
            'expired_at' => 'nullable|date',
        ]);

        $subscription->update($validated);
        return $subscription;
    }

    public function destroy($id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        $subscription->delete();
        return response()->noContent();
    }
}