<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::active()
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->kategori))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->orderByDesc('is_featured')->latest()
            ->paginate(9)->withQueryString();

        return view('programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        abort_unless($program->is_active, 404);

        return view('programs.show', [
            'program' => $program,
            'donations' => $program->donations()->paid()->latest('paid_at')->take(10)->get(),
            'distributions' => $program->distributions()->latest('distributed_at')->take(5)->get(),
            'methods' => PaymentMethod::active()->get(),
            'others' => Program::active()->where('id', '!=', $program->id)->latest()->take(3)->get(),
        ]);
    }
}
