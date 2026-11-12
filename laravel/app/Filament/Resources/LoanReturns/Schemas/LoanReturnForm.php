<?php

namespace App\Filament\Resources\LoanReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoanReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('loan_id')
                ->label('Peminjaman')
                ->relationship('loan', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => '#' . $record->id . ' - ' . $record->user->name)
                ->searchable()
                ->preload()
                ->required(),
            DatePicker::make('returned_at')->label('Tgl Kembali')->default(now())->required(),
            Select::make('condition')
                ->label('Kondisi')
                ->options(['baik' => 'Baik', 'rusak' => 'Rusak'])
                ->default('baik')
                ->required(),
            TextInput::make('late_days')->label('Hari Telat')->numeric()->default(0),
            TextInput::make('fine')->label('Denda')->numeric()->default(0)->prefix('Rp'),
            Textarea::make('notes')->label('Catatan')->columnSpanFull(),
        ]);
    }
}