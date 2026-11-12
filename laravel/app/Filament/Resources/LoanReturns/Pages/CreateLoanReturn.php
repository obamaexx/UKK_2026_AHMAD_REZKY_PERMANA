<?php

namespace App\Filament\Resources\LoanReturns\Pages;

use App\Filament\Resources\LoanReturns\LoanReturnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoanReturn extends CreateRecord
{
    protected static string $resource = LoanReturnResource::class;
}
