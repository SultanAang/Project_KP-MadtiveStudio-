<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use BackedEnum;
use Filament\Schemas\Schema;

class ManageProjectRoadmaps extends ManageRelatedRecords {
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = "roadmaps";

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-map";

    protected static ?string $navigationLabel = "Roadmap";

    public function form(Schema $Schema): Schema {
        return $Schema
            ->schema([
                Forms\Components\TextInput::make("title")
                    ->label("Fitur / Target")
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make("status")
                    ->options([
                        "pending" => "Pending",
                        "in_progress" => "Sedang Dikerjakan",
                        "completed" => "Selesai",
                    ])
                    ->required()
                    ->default("pending"),

                Forms\Components\DatePicker::make("target_date")->label("Target Tanggal"),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute("title")
            ->columns([
                Tables\Columns\TextColumn::make("title")->searchable()->weight("bold"),

                Tables\Columns\TextColumn::make("status")->badge()->color(
                    fn(string $state): string => match ($state) {
                        "pending" => "gray",
                        "in_progress" => "warning",
                        "completed" => "success",
                        default => "gray",
                    },
                ),

                Tables\Columns\TextColumn::make("target_date")->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()->slideOver()])
            ->actions([EditAction::make()->slideOver(), DeleteAction::make()]);
    }
}
