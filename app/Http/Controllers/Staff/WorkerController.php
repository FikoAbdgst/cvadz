<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\WorkerRequest;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerController extends Controller
{
    public function index(Request $request): View
    {
        $workers = Worker::withCount('attendances')->orderBy('name');

        if ($search = trim((string) $request->input('q'))) {
            $workers->where('name', 'like', "%{$search}%");
        }

        return view('staff.workers.index', [
            'pekerja' => $workers->paginate(15)->withQueryString(),
            'search' => trim((string) $request->input('q')),
        ]);
    }

    public function create(): View
    {
        return view('staff.workers.create');
    }

    public function store(WorkerRequest $request): RedirectResponse
    {
        Worker::create($request->validated());

        return redirect()->route('staff.workers.index')
            ->with('success', 'Data pekerja berhasil ditambahkan.');
    }

    public function edit(Worker $pekerja): View
    {
        return view('staff.workers.edit', ['pekerja' => $pekerja]);
    }

    public function update(WorkerRequest $request, Worker $pekerja): RedirectResponse
    {
        $pekerja->update($request->validated());

        return redirect()->route('staff.workers.index')
            ->with('success', 'Data pekerja berhasil diperbarui.');
    }

    public function destroy(Worker $pekerja): RedirectResponse
    {
        $pekerja->delete();

        return redirect()->route('staff.workers.index')
            ->with('success', 'Data pekerja berhasil dihapus.');
    }
}
