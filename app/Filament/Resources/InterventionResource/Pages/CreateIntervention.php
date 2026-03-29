<?php

namespace App\Filament\Resources\InterventionResource\Pages;

use App\Filament\Resources\InterventionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIntervention extends CreateRecord
{
    protected static string $resource = InterventionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user && $user->isService() && $user->service) {
            $data['service_id'] = $user->service->id;
        }

        return $data;
    }
}
