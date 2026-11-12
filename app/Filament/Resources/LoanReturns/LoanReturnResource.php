<?php

namespace App\Filament\Resources\LoanReturns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('loan.user'))
            ->columns([
                TextColumn::make('loan.user.name')->label('Peminjam')->searchable(),
                TextColumn::make('returned_at')->label('Tgl Kembali')->date(),
                TextColumn::make('condition')->label('Kondisi')->badge(),
                TextColumn::make('late_days')->label('Hari Telat'),
                TextColumn::make('fine')->label('Denda')->money('IDR'),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([10, 25])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}