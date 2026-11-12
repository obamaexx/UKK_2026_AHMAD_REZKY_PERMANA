<?php

namespace App\Filament\Resources\LoanReturns\Pages;

use App\Filament\Resources\LoanReturns\LoanReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoanReturn extends EditRecord
{
    protected static string $resource = LoanReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
