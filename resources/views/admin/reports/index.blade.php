@extends('layouts.admin')

@php
    $pageTitle = 'Laporan Aduan Pengguna';
@endphp

@section('admin-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Laporan & Aduan Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar laporan dugaan penipuan, data palsu, atau pelanggaran ketentuan dari pengunjung situs.</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white p-3 rounded-2xl border border-slate-200/90 shadow-sm flex items-center gap-1 overflow-x-auto">
        @php $currentStatus = request('status', ''); @endphp
        <a href="{{ route('admin.reports.index') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === '' ? 'bg-[#0f1e36] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua Aduan ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Pending / Belum Ditinjau ({{ $statusCounts['pending'] }})
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'resolved' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Selesai / Resolved ({{ $statusCounts['resolved'] }})
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'dismissed' ? 'bg-slate-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            Diabaikan ({{ $statusCounts['dismissed'] }})
        </a>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Tanggal Aduan</th>
                        <th class="p-4">Unit Mobil Terlapor</th>
                        <th class="p-4">Alasan & Catatan Pelapor</th>
                        <th class="p-4">Pelapor</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Tindakan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $rep)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-slate-500 whitespace-nowrap">
                                {{ $rep->created_at->format('d M Y, H:i') }}
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $rep->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="p-4">
                                @if($rep->car)
                                    <a href="{{ route('cars.show', $rep->car->slug) }}" target="_blank" class="font-extrabold text-[#0f1e36] hover:text-blue-600 block">
                                        {{ $rep->car->title }} ↗
                                    </a>
                                    <p class="text-slate-400 text-[11px] mt-0.5">Penjual: {{ $rep->car->user->name ?? '-' }} ({{ $rep->car->status }})</p>
                                @else
                                    <span class="text-rose-500 font-bold italic">Iklan Sudah Dihapus</span>
                                @endif
                            </td>
                            <td class="p-4 max-w-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-rose-50 text-rose-700 border border-rose-200 inline-block mb-1">
                                    {{ $rep->reason }}
                                </span>
                                <p class="text-slate-600 italic">"{{ $rep->notes ?? 'Tidak ada catatan khusus.' }}"</p>
                            </td>
                            <td class="p-4 text-slate-600">
                                <p class="font-bold">{{ $rep->reporter->name ?? 'Anonim / Tamu' }}</p>
                                <p class="text-slate-400 text-[10px]">{{ $rep->reporter->email ?? '-' }}</p>
                            </td>
                            <td class="p-4">
                                @php
                                    $statusBadge = match($rep->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'resolved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full border text-[10px] font-extrabold uppercase {{ $statusBadge }}">
                                    {{ $rep->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if($rep->status === 'pending')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Resolve & Suspend Car -->
                                        @if($rep->car && $rep->car->status === 'active')
                                            <form action="{{ route('admin.reports.resolve', $rep) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="take_down_car" value="1">
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 rounded-lg bg-rose-600 text-white hover:bg-rose-700 text-[11px] font-bold transition">
                                                    Resolve & Takedown Mobil
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Resolve Only -->
                                        <form action="{{ route('admin.reports.resolve', $rep) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[11px] font-bold transition">
                                                Tandai Selesai
                                            </button>
                                        </form>

                                        <!-- Dismiss -->
                                        <form action="{{ route('admin.reports.dismiss', $rep) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-[11px] font-bold transition">
                                                Abaikan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Sudah Diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada aduan yang sesuai status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
