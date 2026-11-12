<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('laporan')
                ->label('Cetak Laporan')
                ->url(route('laporan'), shouldOpenInNewTab: true)
                ->visible(fn () => in_array(auth()->user()->role, ['admin', 'petugas'])),
            CreateAction::make(),
        ];
    }
}