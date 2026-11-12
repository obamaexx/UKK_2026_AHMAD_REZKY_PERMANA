<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\ActivityLog;
use App\Models\LoanReturn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Peminjam')->searchable(),
                TextColumn::make('details.tool.name')->label('Alat')->badge(),
                TextColumn::make('loan_date')->label('Tgl Pinjam')->date(),
                TextColumn::make('due_date')->label('Batas Kembali')->date(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'info',
                        'returned' => 'success',
                        default    => 'danger',
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([10, 25])
            ->recordActions([
                // PETUGAS: setujui lewat stored procedure (ada commit/rollback di dalamnya)
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => auth()->user()->role === 'petugas' && $record->status === 'pending')
                    ->action(function ($record) {
                        try {
                            DB::statement('CALL sp_approve_loan(?, ?)', [$record->id, auth()->id()]);

                            ActivityLog::create([
                                'user_id'     => auth()->id(),
                                'action'      => 'approved',
                                'subject'     => 'Loan #' . $record->id,
                                'description' => 'Peminjaman disetujui',
                            ]);

                            Notification::make()->title('Peminjaman disetujui')->success()->send();
                        } catch (\Throwable $e) {
                            $pesan = str_contains($e->getMessage(), 'Stok alat tidak mencukupi')
                                ? 'Stok alat tidak mencukupi'
                                : 'Terjadi kesalahan saat menyetujui';

                            Notification::make()->title('Gagal')->body($pesan)->danger()->send();
                        }
                    }),

                // PETUGAS: tolak
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => auth()->user()->role === 'petugas' && $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()->title('Peminjaman ditolak')->warning()->send();
                    }),

                // PEMINJAM: kembalikan alat, denda dihitung oleh function MySQL
                Action::make('return')
                    ->label('Kembalikan')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => auth()->user()->role === 'peminjam' && $record->status === 'approved')
                    ->action(function ($record) {
                        $today = now()->toDateString();

                        $fine = (int) DB::selectOne(
                            'SELECT fn_hitung_denda(?, ?) AS denda',
                            [$record->due_date->toDateString(), $today]
                        )->denda;

                        // Trigger trg_after_return otomatis menambah stok dan mengubah status
                        LoanReturn::create([
                            'loan_id'     => $record->id,
                            'returned_at' => $today,
                            'late_days'   => intdiv($fine, 5000),
                            'fine'        => $fine,
                        ]);

                        Notification::make()
                            ->title('Alat dikembalikan. Denda: Rp ' . number_format($fine, 0, ',', '.'))
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}