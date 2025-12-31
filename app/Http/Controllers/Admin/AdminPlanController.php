<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Repositories\PlanRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function __construct(protected PlanRepository $planRepository) {}

    public function index(): View
    {
        $plans = $this->planRepository->getAll();
        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_views_limit' => 'required|integer|min:0',
            'chat_limit' => 'required|integer|min:0',
            'interest_limit' => 'required|integer|min:0',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['can_see_contact'] = $request->boolean('can_see_contact');
        $validated['can_chat'] = $request->boolean('can_chat');
        $validated['profile_highlighter'] = $request->boolean('profile_highlighter');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        $this->planRepository->create($validated);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_views_limit' => 'required|integer|min:0',
            'chat_limit' => 'required|integer|min:0',
            'interest_limit' => 'required|integer|min:0',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['can_see_contact'] = $request->boolean('can_see_contact');
        $validated['can_chat'] = $request->boolean('can_chat');
        $validated['profile_highlighter'] = $request->boolean('profile_highlighter');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $this->planRepository->update($plan, $validated);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $this->planRepository->delete($plan);
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}

















