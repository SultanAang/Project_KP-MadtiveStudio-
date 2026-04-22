<?php

namespace App\Filament\Resources\Releases\Pages;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\Releases\ReleaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRelease extends CreateRecord {
    protected static string $resource = ReleaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Menyisipkan ID Drafter yang sedang login saat ini
        $data["created_by"] = Auth::id();
        // dd($data);
        return $data;
    }
}
