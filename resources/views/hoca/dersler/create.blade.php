@extends('layouts.app')

@section('baslik', 'Yeni Ders')

@section('icerik')
    <div class="mx-auto space-y-4">
        <form method="POST" action="{{ route('hoca.dersler.store') }}">
            @csrf
            @include('hoca.dersler._form')
        </form>
    </div>
@endsection
