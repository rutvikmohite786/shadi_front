<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\Religion;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMasterDataController extends Controller
{
    public function __construct(protected MasterDataService $masterDataService) {}

    public function religions(): View
    {
        $religions = $this->masterDataService->getAllReligions();
        return view('admin.master.religions', compact('religions'));
    }

    public function storeReligion(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:100']);
        $this->masterDataService->createReligion($request->only('name', 'is_active'));
        return back()->with('success', 'Religion added successfully.');
    }

    public function updateReligion(Request $request, Religion $religion): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:100']);
        $this->masterDataService->updateReligion($religion, $request->only('name', 'is_active'));
        return back()->with('success', 'Religion updated successfully.');
    }

    public function castes(): View
    {
        $castes = $this->masterDataService->getActiveCastes();
        $religions = $this->masterDataService->getAllReligions();
        return view('admin.master.castes', compact('castes', 'religions'));
    }

    public function storeCaste(Request $request): RedirectResponse
    {
        $request->validate(['religion_id' => 'required|exists:religions,id', 'name' => 'required|string|max:100']);
        $this->masterDataService->createCaste($request->only('religion_id', 'name', 'is_active'));
        return back()->with('success', 'Caste added successfully.');
    }

    public function motherTongues(): View
    {
        $motherTongues = $this->masterDataService->getAllMotherTongues();
        return view('admin.master.mother-tongues', compact('motherTongues'));
    }

    public function storeMotherTongue(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:100']);
        $this->masterDataService->createMotherTongue($request->only('name', 'is_active'));
        return back()->with('success', 'Mother tongue added successfully.');
    }

    public function locations(): View
    {
        $countries = $this->masterDataService->getAllCountries();
        return view('admin.master.locations', compact('countries'));
    }

    public function educations(): View
    {
        $educations = $this->masterDataService->getAllEducations();
        return view('admin.master.educations', compact('educations'));
    }

    public function storeEducation(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:100', 'category' => 'nullable|string|max:50']);
        Education::create($request->only('name', 'category', 'is_active'));
        $this->masterDataService->clearAllCache();
        return back()->with('success', 'Education added successfully.');
    }

    public function occupations(): View
    {
        $occupations = $this->masterDataService->getAllOccupations();
        return view('admin.master.occupations', compact('occupations'));
    }

    public function storeOccupation(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:100', 'category' => 'nullable|string|max:50']);
        Occupation::create($request->only('name', 'category', 'is_active'));
        $this->masterDataService->clearAllCache();
        return back()->with('success', 'Occupation added successfully.');
    }

    public function getCastes(int $religionId): JsonResponse
    {
        return response()->json($this->masterDataService->getCastesByReligion($religionId));
    }

    public function getSubcastes(int $casteId): JsonResponse
    {
        return response()->json($this->masterDataService->getSubcastesByCaste($casteId));
    }

    public function getStates(int $countryId): JsonResponse
    {
        return response()->json($this->masterDataService->getStatesByCountry($countryId));
    }

    public function getCities(int $stateId): JsonResponse
    {
        return response()->json($this->masterDataService->getCitiesByState($stateId));
    }
}















