<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('price_monthly')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('price_yearly')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('max_links')
                    ->required()
                    ->numeric()
                    ->default(25),
                TextInput::make('max_domains')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('features')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
