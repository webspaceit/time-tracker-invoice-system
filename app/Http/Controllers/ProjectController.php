<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('customer')
            ->withCount('timeEntries')
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('name')->get();

        return view('projects.index', compact('projects', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $project = Project::create($data);

        return response()->json(['project' => $project->load('customer')]);
    }

    public function show(Project $project)
    {
        return response()->json(['project' => $project->load('customer')]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $project->update($data);

        return response()->json(['project' => $project->fresh()->load('customer')]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['success' => true]);
    }
}
