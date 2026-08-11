<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        /** @var User $record */
        $record = static::getModel()::create($data);

        if ($role) {
            $record->assignRole($role);
        }

        return $record;
    }
}
