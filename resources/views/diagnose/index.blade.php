@extends('layouts.app')

@section('content')
<div class="container">
	<div class="card" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
		<h1 style="margin:0">Daftar Diagnosa Jamur</h1>
		<a href="{{ route('diagnose.create') }}" class="btn btn-primary">Buat Diagnosa</a>
	</div>

	<div class="card">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Tanggal</th>
					<th>User</th>
					<th>Jumlah Hasil</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse($list as $dj)
				<tr>
					<td>{{ $loop->iteration + ($list->currentPage()-1)*$list->perPage() }}</td>
					<td>{{ $dj->tanggal }}</td>
					<td>{{ $dj->user?->name ?? 'Guest' }}</td>
					<td>{{ $dj->hasilSaws->count() }}</td>
					<td class="actions">
						<a href="{{ route('diagnose.show', $dj) }}" class="btn btn-sm btn-secondary">Lihat</a>
						@php $u = auth()->user(); @endphp
						@if($u && ($u->role ?? '') === 'admin')
						<form action="{{ route('diagnose.destroy', $dj) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus diagnosa ini?');">
							@csrf
							@method('DELETE')
							<button class="btn btn-sm btn-danger">Hapus</button>
						</form>
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="5">Belum ada diagnosa.</td>
				</tr>
				@endforelse
			</tbody>
		</table>

		<div style="margin-top:12px">{{ $list->links() }}</div>
	</div>
</div>
@endsection
