<?php

namespace App\Filament\Resources\Faqs\Pages;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\Faqs\FaqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Menyisipkan ID Drafter yang sedang login saat ini
        $data["created_by"] = Auth::id();
        // dd($data);
        return $data;
    }
}
