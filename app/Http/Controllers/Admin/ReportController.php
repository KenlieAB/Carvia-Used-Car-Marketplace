<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Car;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['car.carModel.brand', 'car.user', 'reporter']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all'       => Report::count(),
            'pending'   => Report::where('status', 'pending')->count(),
            'resolved'  => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports.index', compact('reports', 'statusCounts'));
    }

    public function resolve(Report $report, Request $request)
    {
        $report->update(['status' => 'resolved']);

        // Jika admin juga memilih untuk langsung men-takedown mobilnya
        if ($request->boolean('take_down_car') && $report->car) {
            $report->car->update(['status' => 'rejected']);
            return back()->with('success', 'Laporan ditandai Selesai dan unit mobil terkait telah ditangguhkan.');
        }

        return back()->with('success', 'Laporan berhasil ditandai Selesai.');
    }

    public function dismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);

        return back()->with('success', 'Laporan diabaikan (dismissed).');
    }
}
