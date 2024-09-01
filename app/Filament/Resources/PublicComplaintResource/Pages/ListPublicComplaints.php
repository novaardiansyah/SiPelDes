<?php

namespace App\Filament\Resources\PublicComplaintResource\Pages;

use App\Filament\Resources\PublicComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublicComplaints extends ListRecords
{
    protected static string $resource = PublicComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
