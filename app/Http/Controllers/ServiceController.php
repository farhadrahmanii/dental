<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->orderBy('name')->paginate(15);
        $categories = Service::distinct()->pluck('category')->filter();
        
        return view('financial.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        return view('financial.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $service->load('invoiceItems.invoice.patient');
        
        return view('financial.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('financial.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->invoiceItems()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Cannot delete service that has been used in invoices.');
        }
        
        $service->delete();
        
        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        $status = $service->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('services.index')
            ->with('success', "Service {$status} successfully.");
    }
}