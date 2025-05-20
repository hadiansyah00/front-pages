<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kategori;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KategoriResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Filament\Resources\KategoriResource\Pages\EditKategori;
use App\Filament\Resources\KategoriResource\Pages\ListKategoris;
use App\Filament\Resources\KategoriResource\Pages\CreateKategori;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori Proyek';
    protected static ?string $pluralModelLabel = 'Kategori Proyek';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('kategori-list');
    }
    public static function canEdit($record): bool
    {
        return auth()->user()?->can('kategori-edit');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('kategori-delete');
    }
    public static function canCreate(): bool
    {
        return auth()->user()?->can('kategori-create');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', \Str::slug($state))
                    ),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }

}