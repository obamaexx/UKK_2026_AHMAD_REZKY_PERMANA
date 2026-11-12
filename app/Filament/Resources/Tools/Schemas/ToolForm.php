<?php

namespace App\Filament\Resources\Tools\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ToolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('code')
                ->label('Kode Alat')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('name')->label('Nama Alat')->required(),
            TextInput::make('stock')->label('Stok')->numeric()->minValue(0)->required(),
            Select::make('condition')
                ->label('Kondisi')
                ->options(['baik' => 'Baik', 'rusak' => 'Rusak'])
                ->default('baik')
                ->required(),
        ]);
    }
}