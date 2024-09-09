<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeathLetterResource\Pages;
use App\Filament\Resources\DeathLetterResource\RelationManagers;
use App\Models\DeathLetter;
use App\Models\LetterTemplate;
use App\Models\Resident;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class DeathLetterResource extends Resource
{
  protected static ?string $model = DeathLetter::class;

  protected static ?string $navigationGroup = 'Kelola Surat';
  protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $modelLabel = 'Surat Keterangan Kematian';
  protected static ?string $pluralModelLabel = 'Keterangan Kematian';
  protected static ?int $navigationSort = 40;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('')
          ->schema([
            Forms\Components\Section::make()
              ->description('Lengkapi formulir surat keterangan kematian')
              ->columns(2)
              ->collapsible()
              ->schema([
                Forms\Components\TextInput::make('nik')
                  ->label('NIK')
                  ->numeric()
                  ->live(onBlur: true)
                  ->afterStateUpdated(function (callable $set, $state) {
                    $resident = Resident::where('nik', $state)->first();

                    $set('nama', $resident?->nama ?? '');
                    $set('alamat', $resident?->alamat ?? '');
                    $set('jenis_kelamin', $resident?->jenis_kelamin ?? '');
                  })
                  ->readOnlyOn('edit')
                  ->required(),
                Forms\Components\TextInput::make('nama')
                  ->label('Nama Lengkap')
                  ->dehydrated(false)
                  ->required()
                  ->readOnly(),
                Forms\Components\TextInput::make('jenis_kelamin')
                  ->label('Jenis Kelamin')
                  ->dehydrated(false)
                  ->required()
                  ->readOnly(),
                Forms\Components\TextInput::make('tempat_kematian')
                  ->label('Tempat Kematian')
                  ->placeholder('Rumah Sakit'),
                Forms\Components\TextInput::make('penyebab_kematian')
                  ->label('Penyebab Kematian')
                  ->placeholder('Sakit'),
                Forms\Components\DateTimePicker::make('waktu_kematian')
                  ->label('Waktu Kematian')
                  ->displayFormat('d M Y H:i')
                  ->seconds(false)
                  ->closeOnDateSelection()
                  ->native(false)
                  ->default(now()),
                Forms\Components\Textarea::make('alamat')
                  ->label('Alamat')
                  ->rows(3)
                  ->columnSpanFull(),
              ]),
            Forms\Components\Section::make('')
              ->description('Detail status pengajuan surat')
              ->columns(3)
              ->visibleOn('edit')
              ->schema([
                Forms\Components\TextInput::make('status')
                  ->label('Status')
                  ->dehydrated(false)
                  ->formatStateUsing(fn($state): string => strtoupper($state))
                  ->readOnly(),
                Forms\Components\TextInput::make('kode_surat')
                  ->label('Nomor Surat')
                  ->dehydrated(false)
                  ->readOnly(),
                Forms\Components\TextInput::make('diajukan_oleh')
                  ->label('Pengajuan Oleh')
                  ->dehydrated(false)
                  ->readOnly(),
              ]),
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
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('resident.nama')
          ->label('Nama Lengkap')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('resident.jenis_kelamin')
          ->label('Jenis Kelamin')
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('resident.alamat')
          ->label('Alamat')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('kode_surat')
          ->label('Nomor Surat')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('penyebab_kematian')
          ->label('Penyebab Kematian')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('tempat_kematian')
          ->label('Tempat Kematian')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('waktu_kematian')
          ->label('Waktu Kematian')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('status')
          ->label('Status')
          ->sortable()
          ->badge()
          ->formatStateUsing(fn($state) => strtoupper($state))
          ->color(function ($state): string {
            return match ($state) {
              LetterTemplate::PENGAJUAN => 'warning',
              LetterTemplate::DISETUJUI => 'success',
              LetterTemplate::DITOLAK => 'danger',
            };
          })
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
      ->recordUrl(null)
      ->recordAction(null)
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

          Tables\Actions\ReplicateAction::make()
            ->label('Duplikasi')
            ->excludeAttributes(['kode_surat', 'file_surat'])
            ->requiresConfirmation()
            ->color('info'),

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
      'index' => Pages\ListDeathLetters::route('/'),
      'create' => Pages\CreateDeathLetter::route('/create'),
      'edit' => Pages\EditDeathLetter::route('/{record}/edit'),
    ];
  }
}
