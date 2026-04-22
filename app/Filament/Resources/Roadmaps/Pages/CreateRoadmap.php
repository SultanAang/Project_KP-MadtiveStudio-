<?php

namespace App\Filament\Resources\Roadmaps\Pages;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\Roadmaps\RoadmapResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoadmap extends CreateRecord {
    protected static string $resource = RoadmapResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Menyisipkan ID Drafter yang sedang login saat ini
        $data["created_by"] = Auth::id();
        // dd($data);
        return $data;
    }
}
