@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
        <h1 style="margin:0">Tambah Kriteria</h1>
        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="card">
    <form action="{{ route('admin.kriteria.store') }}" method="POST">
        @include('admin.kriteria.form')
    </form>
    </div>
</div>
@endsection
