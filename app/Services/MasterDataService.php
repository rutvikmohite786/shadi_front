<?php

namespace App\Services;

use App\Models\Caste;
use App\Models\City;
use App\Models\Country;
use App\Models\Education;
use App\Models\MotherTongue;
use App\Models\Occupation;
use App\Models\Religion;
use App\Models\State;
use App\Models\Subcaste;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MasterDataService
{
    protected int $cacheTime = 3600;

    public function getActiveReligions(): Collection
    {
        return Cache::remember('religions.active', $this->cacheTime, fn() => Religion::active()->orderBy('name')->get());
    }

    public function getAllReligions(): Collection
    {
        return Religion::orderBy('name')->get();
    }

    public function createReligion(array $data): Religion
    {
        Cache::forget('religions.active');
        return Religion::create($data);
    }

    public function updateReligion(Religion $religion, array $data): bool
    {
        Cache::forget('religions.active');
        return $religion->update($data);
    }

    public function getActiveCastes(?int $religionId = null): Collection
    {
        $query = Caste::active()->with('religion')->orderBy('name');
        if ($religionId) $query->where('religion_id', $religionId);
        return $query->get();
    }

    public function getCastesByReligion(int $religionId): Collection
    {
        return Cache::remember("castes.religion.{$religionId}", $this->cacheTime, 
            fn() => Caste::active()->where('religion_id', $religionId)->orderBy('name')->get());
    }

    public function createCaste(array $data): Caste
    {
        Cache::forget("castes.religion.{$data['religion_id']}");
        return Caste::create($data);
    }

    public function getActiveSubcastes(?int $casteId = null): Collection
    {
        $query = Subcaste::active()->with('caste')->orderBy('name');
        if ($casteId) $query->where('caste_id', $casteId);
        return $query->get();
    }

    public function getSubcastesByCaste(int $casteId): Collection
    {
        return Cache::remember("subcastes.caste.{$casteId}", $this->cacheTime, 
            fn() => Subcaste::active()->where('caste_id', $casteId)->orderBy('name')->get());
    }

    public function getActiveMotherTongues(): Collection
    {
        return Cache::remember('mother_tongues.active', $this->cacheTime, fn() => MotherTongue::active()->orderBy('name')->get());
    }

    public function getAllMotherTongues(): Collection
    {
        return MotherTongue::orderBy('name')->get();
    }

    public function createMotherTongue(array $data): MotherTongue
    {
        Cache::forget('mother_tongues.active');
        return MotherTongue::create($data);
    }

    public function getActiveCountries(): Collection
    {
        return Cache::remember('countries.active', $this->cacheTime, fn() => Country::active()->orderBy('name')->get());
    }

    public function getAllCountries(): Collection
    {
        return Country::orderBy('name')->get();
    }

    public function getActiveStates(?int $countryId = null): Collection
    {
        $query = State::active()->with('country')->orderBy('name');
        if ($countryId) $query->where('country_id', $countryId);
        return $query->get();
    }

    public function getStatesByCountry(int $countryId): Collection
    {
        return Cache::remember("states.country.{$countryId}", $this->cacheTime, 
            fn() => State::active()->where('country_id', $countryId)->orderBy('name')->get());
    }

    public function getActiveCities(?int $stateId = null): Collection
    {
        $query = City::active()->with('state')->orderBy('name');
        if ($stateId) $query->where('state_id', $stateId);
        return $query->get();
    }

    public function getCitiesByState(int $stateId): Collection
    {
        return Cache::remember("cities.state.{$stateId}", $this->cacheTime, 
            fn() => City::active()->where('state_id', $stateId)->orderBy('name')->get());
    }

    public function getActiveEducations(): Collection
    {
        return Cache::remember('educations.active', $this->cacheTime, 
            fn() => Education::active()->orderBy('category')->orderBy('name')->get());
    }

    public function getAllEducations(): Collection
    {
        return Education::orderBy('category')->orderBy('name')->get();
    }

    public function getActiveOccupations(): Collection
    {
        return Cache::remember('occupations.active', $this->cacheTime, 
            fn() => Occupation::active()->orderBy('category')->orderBy('name')->get());
    }

    public function getAllOccupations(): Collection
    {
        return Occupation::orderBy('category')->orderBy('name')->get();
    }

    public function clearAllCache(): void
    {
        Cache::forget('religions.active');
        Cache::forget('mother_tongues.active');
        Cache::forget('countries.active');
        Cache::forget('educations.active');
        Cache::forget('occupations.active');
    }
}

















