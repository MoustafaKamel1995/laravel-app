<?php

namespace App\Http\Controllers;

use App\Models\BlockedCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlockedCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blockedCountries = BlockedCountry::orderBy('country_name')->get();
        return view('blocked-countries.index', compact('blockedCountries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get list of countries from REST Countries API
        $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca2');
        $countries = collect($response->json())->map(function ($country) {
            return [
                'code' => $country['cca2'],
                'name' => $country['name']['common'],
            ];
        })->sortBy('name')->values()->all();

        // Filter out already blocked countries
        $blockedCountryCodes = BlockedCountry::pluck('country_code')->toArray();
        $availableCountries = collect($countries)->filter(function ($country) use ($blockedCountryCodes) {
            return !in_array($country['code'], $blockedCountryCodes);
        })->values()->all();

        return view('blocked-countries.create', compact('availableCountries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|size:2|unique:blocked_countries',
            'country_name' => 'required|string|max:255',
            'block_reason' => 'nullable|string',
        ]);

        BlockedCountry::create([
            'country_code' => strtoupper($request->country_code),
            'country_name' => $request->country_name,
            'is_blocked' => true,
            'block_reason' => $request->block_reason,
        ]);

        return redirect()->route('blocked-countries.index')
            ->with('success', 'Country blocked successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlockedCountry $blockedCountry)
    {
        return view('blocked-countries.edit', compact('blockedCountry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlockedCountry $blockedCountry)
    {
        $request->validate([
            'is_blocked' => 'required|boolean',
            'block_reason' => 'nullable|string',
        ]);

        $blockedCountry->update([
            'is_blocked' => $request->is_blocked,
            'block_reason' => $request->block_reason,
        ]);

        return redirect()->route('blocked-countries.index')
            ->with('success', 'Country block status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlockedCountry $blockedCountry)
    {
        $blockedCountry->delete();

        return redirect()->route('blocked-countries.index')
            ->with('success', 'Country removed from block list successfully.');
    }
}
