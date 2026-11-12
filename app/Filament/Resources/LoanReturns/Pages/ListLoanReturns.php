<?php

namespace App\Filament\Resources\LoanReturns\Pages;

use App\Filament\Resources\LoanReturns\LoanReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoanReturns extends ListRecords
{
    protected static string $resource = LoanReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
