<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pesanan Masuk';
    protected static ?string $navigationGroup = 'Pesanan';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', ['confirmed', 'processing'])
            ->where('seller_id', auth()->id())
            ->where('payment_status', 'paid') // ✅ hanya yang sudah lunas
            ->count();

        return $count ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pelanggan')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending'    => 'Menunggu',
                                'confirmed'  => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'packed'     => 'Dikemas',
                                'shipped'    => 'Dikirim',
                                'delivered'  => 'Terkirim',
                                'completed'  => 'Selesai',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->maxLength(255)
                            ->visible(fn(Forms\Get $get) => $get('status') === 'packed'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Seller')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Pengiriman')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->disabled()
                            ->rows(3),

                        Forms\Components\TextInput::make('shipping_method')
                            ->label('Metode Pengiriman')
                            ->disabled()
                            ->default('-'),

                        Forms\Components\TextInput::make('grand_total') // ✅ fix dari total -> grand_total
                            ->label('Total')
                            ->prefix('Rp')
                            ->disabled(),

                        // ✅ Tampilkan status pembayaran agar seller tahu
                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending'  => 'Menunggu',
                                'paid'     => 'Lunas',
                                'failed'   => 'Gagal',
                                'refunded' => 'Refund',
                            ])
                            ->disabled() // seller tidak bisa mengubah
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Item')
                    ->counts('items')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('grand_total') // ✅ fix dari total -> grand_total
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                // ✅ Tambah kolom status pembayaran agar seller bisa lihat
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'  => 'Menunggu',
                        'paid'     => 'Lunas',
                        'failed'   => 'Gagal',
                        'refunded' => 'Refund',
                        default    => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending'  => 'gray',
                        'paid'     => 'success',
                        'failed'   => 'danger',
                        'refunded' => 'warning',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'    => 'Menunggu',
                        'confirmed'  => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'packed'     => 'Dikemas',
                        'shipped'    => 'Dikirim',
                        'delivered'  => 'Terkirim',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                        default      => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending'    => 'gray',
                        'confirmed'  => 'primary',
                        'processing' => 'warning',
                        'packed'     => 'warning',
                        'shipped'    => 'info',
                        'delivered'  => 'success',
                        'completed'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->formatStateUsing(
                        fn($state) =>
                        $state
                            ? $state->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
                            : '-'
                    )
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Menunggu',
                        'confirmed'  => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'packed'     => 'Dikemas',
                        'shipped'    => 'Dikirim',
                        'delivered'  => 'Terkirim',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                    ])
                    ->multiple()
                    ->native(false),

                // ✅ Tambah filter payment_status
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending'  => 'Menunggu',
                        'paid'     => 'Lunas',
                        'failed'   => 'Gagal',
                        'refunded' => 'Refund',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    // ✅ hanya bisa diproses jika pembayaran sudah lunas
                    ->visible(fn(Order $record) => $record->status === 'confirmed'
                        && $record->payment_status === 'paid')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'processing']);
                        Notification::make()->success()->title('Pesanan Diproses')->send();
                    }),

                Tables\Actions\Action::make('pack')
                    ->label('Kemas')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->visible(fn(Order $record) => $record->status === 'processing'
                        && $record->payment_status === 'paid') // ✅
                    ->action(function (Order $record) {
                        $record->update(['status' => 'packed']);
                        Notification::make()->success()->title('Pesanan Dikemas')->send();
                    }),

                Tables\Actions\Action::make('ship')
                    ->label('Kirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn(Order $record) => $record->status === 'packed'
                        && $record->payment_status === 'paid') // ✅
                    ->action(function (Order $record) {
                        $prefix = $record->shipping_method === 'ekspres' ? 'EXP' : 'REG';
                        $trackingNumber = $prefix . now()->format('ymd') . rand(1000, 9999);

                        $record->update([
                            'status'          => 'shipped',
                            'tracking_number' => $trackingNumber,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pesanan Dikirim')
                            ->body('Nomor Resi: ' . $trackingNumber)
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('payment_status', 'paid') // ✅ seller hanya lihat yang sudah lunas
            ->whereHas('items.product', function ($q) {
                $q->where('seller_id', auth()->id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}