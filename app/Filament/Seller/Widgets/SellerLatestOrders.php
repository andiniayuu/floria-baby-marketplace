<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SellerLatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $sellerId = auth()->id();

        $query = Order::query()
            ->latest()
            ->whereHas('items', function ($q) use ($sellerId) {
                $q->whereHas('product', function ($q2) use ($sellerId) {
                    $q2->where('seller_id', $sellerId);
                });
            });

        return $table
            ->query($query)
            ->heading('Pesanan Terbaru')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR'),

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
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Order $record) => route('filament.seller.resources.orders.view', $record)),
            ])
            ->defaultPaginationPageOption(5);
    }
}
