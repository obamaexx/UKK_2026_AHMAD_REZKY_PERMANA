<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->role === 'peminjam') {
            $data['user_id'] = auth()->id();
            $data['status'] = 'pending';
        }

        return $data;
    }
}