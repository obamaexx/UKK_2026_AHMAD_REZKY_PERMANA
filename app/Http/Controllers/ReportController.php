<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas']),
            403
        );

        $loans = Loan::with(['user', 'details.tool', 'loanReturn'])
            ->when($request->from, fn ($q) => $q->whereDate('loan_date', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->whereDate('loan_date', '<=', $request->to))
            ->latest()
            ->limit(500)
            ->get();

        return view('laporan', compact('loans'));
    }
}