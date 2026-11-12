<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Alat</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #eee; }
        .filter { margin-top: 15px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <h2>LAPORAN PEMINJAMAN ALAT</h2>
    <p class="sub">Dicetak: {{ now()->format('d-m-Y H:i') }} oleh {{ auth()->user()->name }}</p>

    <form method="GET" class="filter no-print">
        Dari <input type="date" name="from" value="{{ request('from') }}">
        Sampai <input type="date" name="to" value="{{ request('to') }}">
        <button type="submit">Filter</button>
        <button type="button" onclick="window.print()">Cetak</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loan->user->name }}</td>
                    <td>
                        @foreach ($loan->details as $d)
                            {{ $d->tool->name }} ({{ $d->qty }})@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ $loan->loan_date->format('d-m-Y') }}</td>
                    <td>{{ $loan->due_date->format('d-m-Y') }}</td>
                    <td>{{ $loan->loanReturn?->returned_at?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td>Rp {{ number_format($loan->loanReturn->fine ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>