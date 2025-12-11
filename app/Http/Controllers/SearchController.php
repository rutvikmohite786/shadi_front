<?php

namespace App\Http\Controllers;

use App\Services\MasterDataService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService,
        protected MasterDataService $masterDataService
    ) {}

    public function index(): View
    {
        $religions = $this->masterDataService->getActiveReligions();
        $motherTongues = $this->masterDataService->getActiveMotherTongues();
        $countries = $this->masterDataService->getActiveCountries();
        $educations = $this->masterDataService->getActiveEducations();
        $occupations = $this->masterDataService->getActiveOccupations();

        return view('search.index', compact('religions', 'motherTongues', 'countries', 'educations', 'occupations'));
    }

    public function search(Request $request): View
    {
        $user = auth()->user();
        $filters = $request->only([
            'gender', 'age_min', 'age_max', 'height_min', 'height_max',
            'religion_id', 'caste_id', 'mother_tongue_id', 'marital_status',
            'education_id', 'occupation_id', 'country_id', 'state_id', 'city_id',
            'diet', 'annual_income', 'sort_by', 'sort_order',
        ]);

        $results = $this->searchService->search($user, $filters);

        $religions = $this->masterDataService->getActiveReligions();
        $motherTongues = $this->masterDataService->getActiveMotherTongues();
        $countries = $this->masterDataService->getActiveCountries();
        $educations = $this->masterDataService->getActiveEducations();
        $occupations = $this->masterDataService->getActiveOccupations();

        return view('search.results', compact('results', 'filters', 'religions', 'motherTongues', 'countries', 'educations', 'occupations'));
    }

    public function quickSearch(Request $request): View
    {
        $keyword = $request->get('q', '');
        $results = $this->searchService->quickSearch(auth()->user(), $keyword);
        return view('search.quick', compact('results', 'keyword'));
    }
}


