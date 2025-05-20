<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function canView(): bool
    {
        return auth()->user()?->hasRole('user-list');
    }

    protected function authorizeAccess(): void
    {
        abort_unless(auth()->user()?->hasRole('user-edit'), 403);
    }

    protected static bool $shouldRegisterNavigation = false;


}
