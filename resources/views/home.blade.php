@php
    function rupiah($angka){
        $hasil_rupiah = 'Rp' . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="fs-5 fw-bold">Hai {{ Auth::user()->name }}</div>
        @if (session('status') )
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (Auth::user()->role == 'bank')
        <div class="container">
            <div class="row justify-content-center mb-3 mt-3">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header bg-primary text-white fw-bold fs-5">
                                    Saldo
                                </div>
                                <div class="card-body">
                                    {{ rupiah($saldo) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header bg-primary text-white fw-bold fs-5">
                                    Nasabah
                                </div>
                                <div class="card-body" style="font-size: 15px">
                                    {{ $nasabah }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header bg-primary text-white fw-bold fs-5">
                                    Transaksi
                                </div>
                                <div class="card-body" style="font-size: 15px">
                                    {{ $transactions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white fw-bold fs-5">
                        Request Topup
                    </div>
                    <div class="card-body">
                    @foreach($request_topup as $transaksi)
                        {{ $transaksi->credit }}
                        <form action="{{ route('acceptRequest') }}" method="POST">
                        @csrf
                            <div class="row">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <input type="hidden" name="id" id="id" value="{{ $transaksi->id }}">
                                </div>
                                <div class="col d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">SETUJU</button>
                                </div>
                            </div>
                        </form>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (Auth::user()->role == 'siswa')
        <div class="col-md-12">
            <div class="card mb-3 mt-3">
                <div class="card-body fs-6">
                    <div class="row">
                       <div class="col d-flex justify-content-start align-items-center fs-5 fw-bold">
                           Saldo: {{ rupiah($saldo) }}
                       </div>
                       <div class="col d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-primary fs-5" data-bs-toggle="modal" data-bs-target="#exampleModal">Top Up</button>
                            <form action="{{ route('topupNow') }}" method="POST">
                                @csrf
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Masukkan Nominal Top Up</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="number" class="form-control" min="10000" value="10000" name="credit">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn-btn-primary">Top Up Sekarang</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header fs-5 fw-bold bg-primary text-white">Produk</div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($products as $key => $product)
                            <div class="col">
                                <form action="{{ route('addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                <input type="hidden" value="{{ $product->id }}" name="product_id">
                                <input type="hidden" value="{{ $product->price }}" name="price">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        {{ $product->name }}
                                    </div>
                                    <div class="card-body">
                                        <img src="{{ $product->photo }}">
                                        <div>{{ $product->description }}</div>
                                        <div>Harga: {{ rupiah($product->price) }}</div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mb-3">
                                            <input type="number" class="form-control" name="quantity" value="0" min="0">
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Masukkan Keranjang</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header fs-5 fw-bold bg-primary text-white">
                    Keranjang
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($carts as $key => $cart)
                            <li>{{ $cart->product->name }} | {{ $cart->quantity }} * {{ rupiah($cart->price) }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    Total Biaya: {{ rupiah($total_biaya) }}
                    <form action="{{ route('payNow') }}" method="POST">
                        <div class="d-grid gap-2 mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success">Bayar Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fs-5 fw-bold bg-primary text-white">
                    Riwayat Transaksi
                </div>
                <div class="card-body">
                    @foreach ($transactions as $key => $transaction)
                        <div class="row mb-3">
                            <div class="col">
                                <div class="row">
                                    <div class="col fw-bold">
                                        {{ $transaction[0]->order_id }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-secondary" style="font-size: 12px">
                                        {{ $transaction[0]->created_at }}
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center">
                                <a href="{{ route('download', ['order_id' =>$transaction[0]->order_id]) }}" class="btn btn-success" target="_blank">Download</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fs-5 fw-bold bg-primary text-white">
                    Mutasi Wallet
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($mutasi as $data)
                        <li>
                            {{ $data->credit ? $data->credit : 'Debit' }} | {{ $data->debit ? $data->debit : 'Kredit' }} | {{ $data->description }}
                            <span class="badge text-bg-warning">
                            {{ $data->status == 'proses' ? 'PROSES' : ''}}
                            </span>
                        </li
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
