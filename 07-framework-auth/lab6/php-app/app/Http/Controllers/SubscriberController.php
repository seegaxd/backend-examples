<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('dir', 'asc');
        
        $perPage = $request->input('per_page', 10);

        $query = Subscriber::orderBy($sortColumn, $sortDirection);

        $subscribers = $query->paginate($perPage);

        return response()->json($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:subscribers,email|max:255',
            'name'  => 'nullable|string|max:255',
        ]);

        $subscriber = Subscriber::create($validatedData);

        return response()->json($subscriber, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber)
    {
        return response()->json($subscriber);
    }

    public function update(Request $request, Subscriber $subscriber)
    {
        // Валидация
        $validatedData = $request->validate([
            'email' => 'required|email|max:255|unique:subscribers,email,' . $subscriber->id,
            'name'  => 'nullable|string|max:255',
        ]);

        // Обновление
        $subscriber->update($validatedData);

        return response()->json($subscriber);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->json(null, 204);
    }
}