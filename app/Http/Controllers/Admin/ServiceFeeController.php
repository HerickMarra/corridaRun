<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceFee;
use Illuminate\Http\Request;

class ServiceFeeController extends Controller
{
    public function index()
    {
        $fees = ServiceFee::latest()->get();
        return view('admin.service-fees.index', compact('fees'));
    }

    public function create()
    {
        return view('admin.service-fees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        ServiceFee::create([
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.service-fees.index')
            ->with('success', 'Taxa de serviço criada com sucesso!');
    }

    public function edit(ServiceFee $service_fee)
    {
        return view('admin.service-fees.edit', compact('service_fee'));
    }

    public function update(Request $request, ServiceFee $service_fee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $service_fee->update([
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.service-fees.index')
            ->with('success', 'Taxa de serviço atualizada com sucesso!');
    }

    public function destroy(ServiceFee $service_fee)
    {
        $service_fee->delete();

        return redirect()->route('admin.service-fees.index')
            ->with('success', 'Taxa de serviço excluída com sucesso!');
    }
}
