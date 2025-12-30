<?php

namespace App\Repositories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository
{
    public function __construct(protected Plan $model) {}

    public function find(int $id): ?Plan
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Plan
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getAll(): Collection
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->orderBy('sort_order')->get();
    }

    public function getFeatured(): Collection
    {
        return $this->model->active()->featured()->orderBy('sort_order')->get();
    }

    public function create(array $data): Plan
    {
        return $this->model->create($data);
    }

    public function update(Plan $plan, array $data): bool
    {
        return $plan->update($data);
    }

    public function delete(Plan $plan): bool
    {
        return $plan->delete();
    }
}
















