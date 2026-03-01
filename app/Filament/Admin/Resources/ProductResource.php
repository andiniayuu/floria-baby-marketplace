<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Semua Produk';
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Select::make('seller_id')
                            ->label('Seller')
                            ->relationship('seller', 'name', fn($query) => $query->where('role', 'seller'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn(string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->unique(Product::class, 'sku', ignoreRecord: true)
                            ->helperText('Kode unik produk (opsional)'),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('images')
                            ->label('Gambar Produk')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('products')
                            ->maxFiles(5)
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675')
                            ->imageResizeUpscale(false)
                            ->reorderable()
                            ->columnSpanFull()
                            ->helperText('Upload maksimal 5 gambar, ukuran max 2MB per gambar'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Stok')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Jual')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),

                        Forms\Components\TextInput::make('compare_price')
                            ->label('Harga Coret (Sebelum Diskon)')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->helperText('Kosongkan jika tidak ada diskon'),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('weight')
                            ->label('Berat (gram)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('gr')
                            ->helperText('Untuk kalkulasi ongkir'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Visibilitas')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Produk tampil di website jika aktif'),

                        // Forms\Components\Toggle::make('is_featured')
                        //     ->label('Produk Unggulan')
                        //     ->default(false)
                        //     ->inline(false)
                        //     ->helperText('Tampil di section unggulan homepage'),

                        // Forms\Components\Toggle::make('is_feature')
                        //     ->label('Produk Feature')
                        //     ->default(false)
                        //     ->inline(false)
                        //     ->helperText('Tampil di banner / highlight khusus'),

                        // Forms\Components\Toggle::make('on_sale')
                        //     ->label('Sedang Diskon')
                        //     ->default(false)
                        //     ->inline(false)
                        //     ->helperText('Tandai produk ini sedang dalam promo'),

                        // Forms\Components\Toggle::make('in_stock')
                        //     ->label('Tersedia (In Stock)')
                        //     ->default(true)
                        //     ->inline(false)
                        //     ->helperText('Override status ketersediaan produk'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Gambar')
                    ->circular()
                    ->stacked()
                    ->limit(3),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('compare_price')
                    ->label('Harga Coret')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('on_sale')
                    ->label('Diskon')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('seller')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('on_sale')
                    ->label('Sedang Diskon')
                    ->boolean()
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Produk Unggulan')
                    ->boolean()
                    ->native(false),

                Tables\Filters\Filter::make('stock_empty')
                    ->label('Stok Habis')
                    ->query(fn($query) => $query->where('stock', '<=', 0)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_sale')
                        ->label('Tandai Diskon')
                        ->icon('heroicon-o-tag')
                        ->color('warning')
                        ->action(fn($records) => $records->each->update(['on_sale' => true]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
