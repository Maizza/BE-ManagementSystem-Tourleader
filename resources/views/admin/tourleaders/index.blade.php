@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Tour Leader</h1>
    <a href="{{ route('tourleaders.create') }}" class="btn btn-primary mb-3"> + Tambah Tour Leader</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Kloter</th> {{-- kolom baru --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
               @foreach($tourleaders as $leader)
               <tr>
                    <td>{{ $leader->name }}</td>
                    <td>{{ $leader->email }}</td>
                    <td>
                        @if($leader->kloter)
                        {{ $leader->kloter->nama }} <br>
                    <small>{{ $leader->kloter->tanggal }}</small>
                    @else
                    -
                    @endif
                </td>
                <td>
                    <a href="{{ route('tourleaders.edit', $leader->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('tourleaders.destroy', $leader->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
