<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterTemplateResource\Pages;
use App\Filament\Resources\LetterTemplateResource\RelationManagers;
use App\Models\LetterTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class LetterTemplateResource extends Resource
{
  protected static ?string $model = LetterTemplate::class;

  protected static ?string $navigationGroup = 'Menu Utama';
  protected static ?string $navigationIcon = 'heroicon-o-document';
  protected static ?string $modelLabel = 'Master Surat';
  protected static ?int $navigationSort = 30;

  public static function form(Form $form): Form 
  {
    return $form
      ->schema([
        Forms\Components\Section::make('')
          ->description('Lengkapi formulir master surat')
          ->columns(2)
          ->schema([
            Forms\Components\TextInput::make('judul')
              ->label('Nama Surat')
              ->required(),
            Forms\Components\TextInput::make('kode')
              ->label('Kode Surat')
              ->required(),
            Forms\Components\TextInput::make('nomor')
              ->label('Nomor Surat')
              ->numeric()
              ->default(101)
              ->required(),
            Forms\Components\TextInput::make('pejabat')
              ->label('Pejabat Penandatangan')
              ->placeholder('Kepala Desa')
              ->required(),
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
        Tables\Columns\TextColumn::make('judul')
          ->label('Nama Surat')
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('kode')
          ->label('Kode Surat')
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('nomor')
          ->label('Nomor Surat')
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('pejabat')
          ->label('Pejabat Penandatangan')
          ->searchable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Dibuat pada')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Diubah pada')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('judul', 'asc')
      ->recordUrl(null)
      ->recordAction(null)
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->color('warning'),
            
          Tables\Actions\EditAction::make()
            ->color('primary'),
          
          // Tables\Actions\DeleteAction::make(),

          ActivityLogTimelineTableAction::make('Activities')
            ->color('info'),
        ])
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          // Tables\Actions\DeleteBulkAction::make(),
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
      'index'  => Pages\ListLetterTemplates::route('/'),
      'create' => Pages\CreateLetterTemplate::route('/create'),
      'edit'   => Pages\EditLetterTemplate::route('/{record}/edit'),
    ];
  }
}
