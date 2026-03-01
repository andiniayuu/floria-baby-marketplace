<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Platform - Floria Baby</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; background: #fff; }

  .header { border-bottom: 2px solid #e91e8c; padding-bottom: 10px; margin-bottom: 12px; }
  .brand { font-size: 17px; font-weight: bold; color: #e91e8c; float: left; }
  .brand small { display: block; font-size: 8.5px; font-weight: normal; color: #999; }
  .doc-right { float: right; text-align: right; font-size: 8.5px; color: #666; line-height: 1.8; }
  .doc-tag { display: inline-block; background: #e91e8c; color: white; font-size: 7.5px; font-weight: bold; padding: 2px 7px; border-radius: 2px; text-transform: uppercase; margin-bottom: 2px; }

  .info-bar { background: #1f2937; color: #d1d5db; padding: 7px 12px; border-radius: 3px; font-size: 8.5px; margin-bottom: 12px; }
  .info-bar strong { color: #f9a8d4; }

  .sum-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4px; color: #888; margin-bottom: 5px; }
  .sum-grid { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
  .sum-grid td { border: 1px solid #e5e7eb; border-top: 2px solid #e91e8c; padding: 7px 8px; text-align: center; width: 16.66%; }
  .slabel { font-size: 7px; color: #aaa; text-transform: uppercase; margin-bottom: 3px; }
  .sval { font-size: 13px; font-weight: bold; color: #111; }
  .sval.pink { color: #e91e8c; font-size: 11px; }
  .snote { font-size: 7px; color: #ccc; margin-top: 2px; }

  .stbl { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
  .stbl td { padding: 5px 8px; text-align: center; font-size: 8px; font-weight: bold; }
  .sg { background: #f3f4f6; color: #374151; }
  .sy { background: #fef9c3; color: #713f12; }
  .sb { background: #dbeafe; color: #1e40af; }
  .so { background: #ffedd5; color: #7c2d12; }
  .sp { background: #f3e8ff; color: #581c87; }
  .si { background: #e0e7ff; color: #312e81; }
  .st { background: #ccfbf1; color: #134e4a; }
  .sgreen { background: #dcfce7; color: #14532d; }
  .sr { background: #fee2e2; color: #7f1d1d; }

  .sec { font-size: 8.5px; font-weight: bold; text-transform: uppercase; color: #555; border-bottom: 1px solid #e91e8c; padding-bottom: 3px; margin-bottom: 7px; display: inline-block; }

  table.t { width: 100%; border-collapse: collapse; font-size: 9px; }
  table.t thead tr { background: #1f2937; }
  table.t thead th { padding: 5px 7px; color: #f3f4f6; text-align: left; font-size: 8px; font-weight: bold; border: none; }
  table.t thead th.c { text-align: center; }
  table.t thead th.r { text-align: right; }
  table.t tbody tr { border-bottom: 1px solid #f5f5f5; }
  table.t tbody tr.alt { background: #fdf2f8; }
  table.t tbody td { padding: 5px 7px; border: none; vertical-align: middle; }
  table.t tbody td.c { text-align: center; }
  table.t tbody td.r { text-align: right; }

  tr.foot-row { background: #1f2937; border-top: 2px solid #e91e8c; }
  tr.foot-row td { padding: 6px 7px; color: white; font-weight: bold; font-size: 9px; border: none; }
  tr.foot-row td.r { text-align: right; color: #f9a8d4; }

  .no { color: #ccc; }
  .nm { font-weight: bold; color: #111; }
  .em { font-size: 7.5px; color: #aaa; margin-top: 1px; }
  .pv { font-weight: bold; color: #e91e8c; }
  .chip { font-family: DejaVu Sans Mono, monospace; font-size: 8px; background: #f3f4f6; padding: 1px 4px; border-radius: 2px; color: #444; }

  .bx { display: inline-block; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
  .bc  { background: #dcfce7; color: #166534; }
  .bsh { background: #dbeafe; color: #1e40af; }
  .bd  { background: #ede9fe; color: #4c1d95; }
  .bpr { background: #fef9c3; color: #713f12; }
  .bpk { background: #f3e8ff; color: #6b21a8; }
  .bco { background: #dbeafe; color: #1d4ed8; }
  .bpe { background: #f3f4f6; color: #374151; }
  .bca { background: #fee2e2; color: #7f1d1d; }

  .rs { display: inline-block; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
  .rs.seller { background: #ede9fe; color: #5b21b6; }
  .rs.buyer  { background: #ccfbf1; color: #065f46; }

  .twocol { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-left: -8px; width: calc(100% + 16px); margin-bottom: 12px; }
  .twocol > tbody > tr > td { vertical-align: top; width: 50%; }
  .botcol { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-left: -8px; width: calc(100% + 16px); }
  .botcol > tbody > tr > td.main { vertical-align: top; width: 62%; }
  .botcol > tbody > tr > td.side { vertical-align: top; width: 38%; }

  .footer { margin-top: 12px; border-top: 1px solid #e5e7eb; padding-top: 7px; font-size: 7.5px; color: #bbb; }
  .fl { float: left; }
  .fr { float: right; font-weight: bold; color: #e91e8c; font-size: 8.5px; }
  .clearfix::after { content: ''; display: table; clear: both; }
</style>
</head>
<body>

@php
  use Carbon\Carbon;

  $statusDef = [
    'pending'          => ['label' => 'Menunggu',     'cls' => 'sg'],
    'payment_uploaded' => ['label' => 'Bukti Bayar',  'cls' => 'sy'],
    'confirmed'        => ['label' => 'Dikonfirmasi', 'cls' => 'sb'],
    'processing'       => ['label' => 'Diproses',     'cls' => 'so'],
    'packed'           => ['label' => 'Dikemas',      'cls' => 'sp'],
    'shipped'          => ['label' => 'Dikirim',      'cls' => 'si'],
    'delivered'        => ['label' => 'Terkirim',     'cls' => 'st'],
    'completed'        => ['label' => 'Selesai',      'cls' => 'sgreen'],
    'cancelled'        => ['label' => 'Dibatalkan',   'cls' => 'sr'],
    'rejected'         => ['label' => 'Ditolak',      'cls' => 'sr'],
  ];
  $badgeDef = [
    'completed' => 'bc', 'shipped' => 'bsh', 'delivered' => 'bd',
    'processing' => 'bpr', 'packed' => 'bpk', 'confirmed' => 'bco',
    'pending' => 'bpe', 'cancelled' => 'bca',
  ];
  $labelDef = [
    'pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'processing' => 'Diproses',
    'packed' => 'Dikemas', 'shipped' => 'Dikirim', 'delivered' => 'Terkirim',
    'completed' => 'Selesai', 'cancelled' => 'Dibatalkan',
  ];
@endphp

<div class="header clearfix">
  <div class="brand">Floria Baby <small>Marketplace Platform</small></div>
  <div class="doc-right">
    <span class="doc-tag">Laporan Admin</span><br>
    Periode: {{ Carbon::parse($dateFrom)->format('d M Y') }} &ndash; {{ Carbon::parse($dateTo)->format('d M Y') }}<br>
    Dicetak: {{ now()->format('d M Y, H:i') }} WIB
  </div>
</div>

<div class="info-bar">
  <strong>Laporan Platform Floria Baby</strong>
  &nbsp;|&nbsp; Admin: {{ auth()->user()->name ?? 'Admin' }}
  &nbsp;|&nbsp; {{ Carbon::parse($dateFrom)->format('d M Y') }} s/d {{ Carbon::parse($dateTo)->format('d M Y') }}
</div>

<div class="sum-title">Ringkasan Platform</div>
<table class="sum-grid">
  <tr>
    <td>
      <div class="slabel">Pendapatan</div>
      <div class="sval pink">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      <div class="snote">pesanan lunas</div>
    </td>
    <td>
      <div class="slabel">Pesanan</div>
      <div class="sval">{{ number_format($totalOrders) }}</div>
      <div class="snote">{{ number_format($paidOrders) }} lunas</div>
    </td>
    <td>
      <div class="slabel">Seller</div>
      <div class="sval">{{ number_format($totalSellers) }}</div>
      <div class="snote">+{{ $newSellers }} baru</div>
    </td>
    <td>
      <div class="slabel">Pembeli</div>
      <div class="sval">{{ number_format($totalBuyers) }}</div>
      <div class="snote">+{{ $newBuyers }} baru</div>
    </td>
    <td>
      <div class="slabel">Produk</div>
      <div class="sval">{{ number_format($totalProducts) }}</div>
      <div class="snote">terdaftar</div>
    </td>
    <td>
      <div class="slabel">Rata-rata Order</div>
      <div class="sval" style="font-size:11px;">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
      <div class="snote">per pesanan</div>
    </td>
  </tr>
</table>

<div class="sum-title">Distribusi Status Pesanan</div>
<table class="stbl">
  <tr>
    @foreach($statusDef as $key => $cfg)
    @php $cnt = $orderStatus->get($key)?->total ?? 0; @endphp
    @if($cnt > 0)
    <td class="{{ $cfg['cls'] }}">
      <div style="font-size:13px;font-weight:bold;">{{ $cnt }}</div>
      <div>{{ $cfg['label'] }}</div>
    </td>
    @endif
    @endforeach
  </tr>
</table>

<table class="twocol">
  <tbody><tr>
    <td>
      <div class="sec">Top Seller</div>
      <table class="t">
        <thead><tr>
          <th style="width:7%">#</th>
          <th>Seller</th>
          <th class="c" style="width:13%">Order</th>
          <th class="r" style="width:32%">Pendapatan</th>
        </tr></thead>
        <tbody>
          @forelse($topSellers as $i => $s)
          <tr class="{{ $loop->even ? 'alt' : '' }}">
            <td class="no">{{ $i+1 }}</td>
            <td><div class="nm">{{ $s->name }}</div><div class="em">{{ $s->email }}</div></td>
            <td class="c">{{ $s->jumlah_order }}</td>
            <td class="r pv" style="font-size:8.5px;">Rp {{ number_format($s->total_pendapatan, 0, ',', '.') }}</td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:10px;color:#bbb;font-style:italic;">Belum ada data</td></tr>
          @endforelse
        </tbody>
        @if($topSellers->count())
        <tr class="foot-row">
          <td colspan="2">{{ $topSellers->count() }} seller</td>
          <td class="c" style="color:white;">{{ $topSellers->sum('jumlah_order') }}</td>
          <td class="r">Rp {{ number_format($topSellers->sum('total_pendapatan'), 0, ',', '.') }}</td>
        </tr>
        @endif
      </table>
    </td>

    <td>
      <div class="sec">Produk Terlaris</div>
      <table class="t">
        <thead><tr>
          <th style="width:7%">#</th>
          <th>Produk</th>
          <th class="c" style="width:14%">Terjual</th>
          <th class="r" style="width:32%">Pendapatan</th>
        </tr></thead>
        <tbody>
          @forelse($topProducts as $i => $p)
          <tr class="{{ $loop->even ? 'alt' : '' }}">
            <td class="no">{{ $i+1 }}</td>
            <td><div class="nm" style="font-size:8.5px;">{{ \Str::limit($p->name, 26) }}</div><div class="em">{{ $p->seller_name }}</div></td>
            <td class="c">{{ number_format($p->terjual) }}</td>
            <td class="r pv" style="font-size:8.5px;">Rp {{ number_format($p->pendapatan, 0, ',', '.') }}</td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:10px;color:#bbb;font-style:italic;">Belum ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </td>
  </tr></tbody>
</table>

<table class="botcol">
  <tbody><tr>
    <td class="main">
      <div class="sec">Pesanan Terbaru</div>
      <table class="t">
        <thead><tr>
          <th style="width:13%">No.</th>
          <th style="width:23%">Pembeli</th>
          <th style="width:20%">Seller</th>
          <th class="c" style="width:18%">Status</th>
          <th class="r" style="width:26%">Total</th>
        </tr></thead>
        <tbody>
          @forelse($recentOrders as $order)
          @php $bc = $badgeDef[$order->status] ?? 'bpe'; $bl = $labelDef[$order->status] ?? ucfirst($order->status); @endphp
          <tr class="{{ $loop->even ? 'alt' : '' }}">
            <td><span class="chip">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span></td>
            <td><div class="nm" style="font-size:8.5px;">{{ $order->user?->name ?? '-' }}</div></td>
            <td style="color:#aaa;font-size:8px;">{{ $order->seller?->name ?? '-' }}</td>
            <td class="c"><span class="bx {{ $bc }}">{{ $bl }}</span></td>
            <td class="r pv" style="font-size:8.5px;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:10px;color:#bbb;font-style:italic;">Belum ada pesanan</td></tr>
          @endforelse
        </tbody>
      </table>
    </td>

    <td class="side">
      <div class="sec">Pendaftar Baru</div>
      <table class="t">
        <thead><tr>
          <th>Nama</th>
          <th class="c" style="width:26%">Role</th>
        </tr></thead>
        <tbody>
          @forelse($newRegistrants as $user)
          <tr class="{{ $loop->even ? 'alt' : '' }}">
            <td>
              <div class="nm" style="font-size:8.5px;">{{ $user->name }}</div>
              <div class="em">{{ \Str::limit($user->email, 24) }}</div>
            </td>
            <td class="c">
              <span class="rs {{ $user->role === 'seller' ? 'seller' : 'buyer' }}">
                {{ $user->role === 'seller' ? 'Seller' : 'Pembeli' }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="2" style="text-align:center;padding:10px;color:#bbb;font-style:italic;">Tidak ada</td></tr>
          @endforelse
        </tbody>
      </table>
    </td>
  </tr></tbody>
</table>

<div class="footer clearfix">
  <div class="fl">Dokumen digenerate otomatis oleh sistem Floria Baby. Sah tanpa tanda tangan basah.</div>
  <div class="fr">Floria Baby &copy; {{ now()->year }}</div>
</div>

</body>
</html>