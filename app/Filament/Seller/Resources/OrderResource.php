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
            ->whereHas('items', function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('seller_id', auth()->id());
                });
            })
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

                        Forms\Components\TextInput::make('user.name')
                            ->label('Pelanggan')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'confirmed' => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'packed' => 'Dikemas',
                                'shipped' => 'Dikirim',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->maxLength(255)
                            ->visible(fn(Forms\Get $get) => in_array($get('status'), ['shipped'])),

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
                            ->disabled(),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total')
                            ->prefix('Rp')
                            ->disabled(),
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

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'confirmed' => 'primary',
                        'processing' => 'warning',
                        'packed' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'packed' => 'Dikemas',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Sampai',
                        'completed' => 'Selesai',
                    ])
                    ->multiple()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn(Order $record) => $record->status === 'confirmed')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'processing']);
                        Notification::make()->success()->title('Pesanan Diproses')->send();
                    }),

                Tables\Actions\Action::make('pack')
                    ->label('Kemas')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->visible(fn(Order $record) => $record->status === 'processing')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'packed']);
                        Notification::make()->success()->title('Pesanan Dikemas')->send();
                    }),

                Tables\Actions\Action::make('ship')
                    ->label('Kirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn(Order $record) => $record->status === 'packed')
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'shipped',
                            'tracking_number' => $data['tracking_number'],
                        ]);
                        Notification::make()->success()->title('Pesanan Dikirim')->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('payment_status', 'paid')
            ->whereHas('items', function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('seller_id', auth()->id());
                });
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
