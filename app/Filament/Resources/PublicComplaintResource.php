<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicComplaintResource\Pages;
use App\Filament\Resources\PublicComplaintResource\RelationManagers;
use App\Models\PublicComplaint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class PublicComplaintResource extends Resource
{
  protected static ?string $model = PublicComplaint::class;

  protected static ?string $navigationGroup = 'Menu Utama';
  protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
  protected static ?string $modelLabel = 'Pengaduan Masyarakat';
  protected static ?int $navigationSort = 20;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('')
          ->description('Lengkapi formulir pengaduan masyarakat')
          ->columns(2)
          ->schema([
            Forms\Components\TextInput::make('resident_nik')
              ->label('NIK')
              ->required(),
            Forms\Components\TextInput::make('resident_nama')
              ->label('Nama Lengkap')
              ->required(),
            Forms\Components\Textarea::make('description')
              ->label('Pengaduan')
              ->rows(5)
              ->columnSpanFull()
              ->required(),
            Forms\Components\FileUpload::make('attachment')
              ->label('Lampiran')
              ->directory('public-complaint')
              ->multiple()
              ->image()
              ->imageEditor()
              ->downloadable()
              ->previewable(),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('index')
          ->rowIndex()
          ->label('#'),
        Tables\Columns\TextColumn::make('resident.nik')
          ->label('NIK')
          ->sortable()
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('resident.nama')
          ->label('Nama Lengkap')
          ->sortable()
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('description')
          ->label('Pengaduan')
          ->wrap()
          ->lineClamp(3)
          ->searchable()
          ->toggleable(),
        Tables\Columns\ImageColumn::make('attachment')
          ->label('Lampiran')
          ->stacked(3)
          ->toggleable(),
      ])
      ->recordUrl(null)
      ->defaultSort('id', 'desc')
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->color('warning'),
            
          Tables\Actions\EditAction::make()
            ->color('primary'),
          
          Tables\Actions\DeleteAction::make(),

          ActivityLogTimelineTableAction::make('Activities')
            ->color('info'),
        ])
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListPublicComplaints::route('/'),
      'create' => Pages\CreatePublicComplaint::route('/create'),
      'edit' => Pages\EditPublicComplaint::route('/{record}/edit'),
    ];
  }
}
