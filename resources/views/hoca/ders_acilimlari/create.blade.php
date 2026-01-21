@extends('layouts.app')
@section('baslik', 'Yeni Ders Açılışı')

@section('icerik')
    <div class="mx-auto space-y-4">
        <form method="POST" action="{{ route('hoca.ders_acilimlari.store') }}">
            @csrf
            @include('hoca.ders_acilimlari._form')
        </form>
    </div>
@endsection
