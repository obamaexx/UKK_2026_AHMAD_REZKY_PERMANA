<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Hanya admin yang memilih peminjam; untuk peminjam diisi otomatis
            Select::make('user_id')
                ->label('Peminjam')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->visible(fn () => auth()->user()->role === 'admin')
                ->required(fn () => auth()->user()->role === 'admin'),

            DatePicker::make('loan_date')
                ->label('Tanggal Pinjam')
                ->default(now())
                ->required(),

            DatePicker::make('due_date')
                ->label('Batas Kembali')
                ->after('loan_date')
                ->required(),

            Select::make('status')
                ->options([
                    'pending'  => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'returned' => 'Dikembalikan',
                ])
                ->default('pending')
                ->visible(fn () => auth()->user()->role === 'admin'),

            Repeater::make('details')
                ->label('Alat yang dipinjam')
                ->relationship()
                ->schema([
                    Select::make('tool_id')
                        ->label('Alat')
                        ->relationship('tool', 'name', modifyQueryUsing: fn ($query) => $query->where('stock', '>', 0))
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('qty')
                        ->label('Jumlah')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),
                ])
                ->minItems(1)
                ->columnSpanFull(),

            Textarea::make('notes')
                ->label('Catatan')
                ->columnSpanFull(),
        ]);
    }
}