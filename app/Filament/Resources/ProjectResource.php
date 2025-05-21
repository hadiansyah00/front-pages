<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

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
                ->disk('public')
                ->directory('projects') // Simpan ke storage/app/public/projects
                ->preserveFilenames()
                ->image()
                ->imageEditor()
                ->visibility('public')
                ->maxSize(2048), // Aktifkan upload multiple file

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
