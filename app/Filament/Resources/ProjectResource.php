<?php

namespace App\Filament\Resources;

use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Str;
use App\Filament\Resources\ProjectResource\Pages;

/**
 * @extends Resource<Project>
 */
class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('proyek-list') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('proyek-edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('proyek-delete') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('proyek-create') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('judul')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),
            TextInput::make('slug')
                ->disabled()
                ->required(),
            Select::make('kategori_id')
                ->relationship('kategori', 'nama_kategori')
                ->required(),
            Textarea::make('deskripsi')
                ->rows(5),
            TextInput::make('tahun_proyek')
                ->label('Tahun Proyek')
                ->numeric()
                ->minValue(2000)
                ->maxValue((int) date('Y')),
            FileUpload::make('gambar')
                ->label('Gambar Proyek')
                ->multiple()
                ->reorderable()
                ->preserveFilenames()
                ->directory('projects')
                ->maxFiles(10)
                ->enableOpen()
                ->enableDownload(),
            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'publish' => 'Publish',
                ])
                ->default('draft')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->sortable()->searchable(),
                TextColumn::make('kategori.nama_kategori'),
                TextColumn::make('tahun_proyek'),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'publish',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'publish' => 'Publish',
                ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
