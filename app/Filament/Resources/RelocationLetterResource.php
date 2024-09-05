<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelocationLetterResource\Pages;
use App\Filament\Resources\RelocationLetterResource\RelationManagers;
use App\Models\RelocationLetter;
use App\Models\Resident;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class RelocationLetterResource extends Resource
{
  protected static ?string $model = RelocationLetter::class;

  protected static ?string $navigationGroup = 'Kelola Surat';
  protected static ?string $navigationIcon = 'heroicon-o-document-text';
  protected static ?string $modelLabel = 'Surat Keterangan Pindah';
  protected static ?string $pluralModelLabel = 'Keterangan Pindah';
  protected static ?int $navigationSort = 10;

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
                    $set('alamat_asal', $resident?->alamat ?? '');

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
                Forms\Components\TextInput::make('jumlah_keluarga')
                  ->label('Jumlah Keluarga')
                  ->numeric()
                  ->default(1)
                  ->hintIcon('heroicon-m-question-mark-circle', tooltip: fn(?string $state) => 'Jumlah keluarga yang pindah/datang.')
                  ->required(),
                Forms\Components\Textarea::make('alamat_asal')
                  ->label('Alamat Sekarang')
                  ->rows(3)
                  ->required(),
                Forms\Components\Textarea::make('alamat_tujuan')
                  ->label('Alamat Tujuan')
                  ->rows(3)
                  ->required(),
              ]),
            Forms\Components\Section::make('')
              ->description('Detail status pengajuan surat')
              ->columns(3)
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
        Tables\Columns\TextColumn::make('jumlah_keluarga')
          ->label('Jumlah Keluarga')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('kode_surat')
          ->label('Nomor Surat')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('alamat_asal')
          ->label('Alamat Asal')
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('alamat_tujuan')
          ->label('Alamat Tujuan')
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('status')
          ->label('Status')
          ->sortable()
          ->badge()
          ->formatStateUsing(fn($state) => strtoupper($state))
          ->color(function ($state): string {
            return match ($state) {
              RelocationLetter::PENGAJUAN => 'warning',
              RelocationLetter::DISETUJUI => 'success',
              RelocationLetter::DITOLAK => 'danger',
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
      'index' => Pages\ListRelocationLetters::route('/'),
      'create' => Pages\CreateRelocationLetter::route('/create'),
      'edit' => Pages\EditRelocationLetter::route('/{record}/edit'),
    ];
  }
}
