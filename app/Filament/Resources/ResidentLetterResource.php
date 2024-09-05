<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentLetterResource\Pages;
use App\Filament\Resources\ResidentLetterResource\RelationManagers;
use App\Models\Resident;
use App\Models\ResidentLetter;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ResidentLetterResource extends Resource
{
  protected static ?string $model = ResidentLetter::class;

  protected static ?string $navigationGroup = 'Kelola Surat';
  protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $modelLabel = 'Surat Keterangan Penduduk';
  protected static ?string $pluralModelLabel = 'Keterangan Penduduk';
  protected static ?int $navigationSort = 20;

  public static function form(Form $form): Form
  {
    return $form
    ->schema([
      Forms\Components\Section::make('')
        ->schema([
          Forms\Components\Section::make('')
            ->description('Lengkapi formulir surat keterangan pindah')
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

                  $tempat_lahir = $resident?->tempat_lahir;
                  $tanggal_lahir = $resident?->tanggal_lahir ? Carbon::parse($resident->tanggal_lahir)->translatedFormat('d F Y') : '';

                  $set('tempat_lahir', $tempat_lahir && $tanggal_lahir ? "$tempat_lahir, $tanggal_lahir" : '');
                })
                ->readOnlyOn('edit')
                ->required(),
              Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap')
                ->dehydrated(false)
                ->readOnly(),
              Forms\Components\TextInput::make('tempat_lahir')
                ->label('Tempat/Tanggal Lahir')
                ->dehydrated(false)
                ->readOnly(),
              Forms\Components\TextInput::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->dehydrated(false)
                ->readOnly(),
              Forms\Components\Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3)
                ->required()
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
        Tables\Columns\TextColumn::make('status')
          ->label('Status')
          ->sortable()
          ->badge()
          ->formatStateUsing(fn($state) => strtoupper($state))
          ->color(function ($state): string {
            return match ($state) {
              ResidentLetter::PENGAJUAN => 'warning',
              ResidentLetter::DISETUJUI => 'success',
              ResidentLetter::DITOLAK => 'danger',
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
      'index' => Pages\ListResidentLetters::route('/'),
      'create' => Pages\CreateResidentLetter::route('/create'),
      'edit' => Pages\EditResidentLetter::route('/{record}/edit'),
    ];
  }
}
