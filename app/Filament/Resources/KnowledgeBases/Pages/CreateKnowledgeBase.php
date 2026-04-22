<?php

namespace App\Filament\Resources\KnowledgeBases\Pages;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\KnowledgeBases\KnowledgeBaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKnowledgeBase extends CreateRecord {
    protected static string $resource = KnowledgeBaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Menyisipkan ID Drafter yang sedang login saat ini
        $data["created_by"] = Auth::id();
        // dd($data);
        return $data;
    }
}
