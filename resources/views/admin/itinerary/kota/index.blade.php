@extends('layouts.app')

@section('content')
<div class="container">

  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">Pilihan Kota</h4>
    <a href="{{ route('admin.itinerary.kota.create') }}" class="btn btn-primary">+ Tambah Kota</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nama Kota</th>
            <th width="150">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cities as $city)
            <tr>
              <td>{{ $city->name }}</td>
              <td>
                <a href="{{ route('admin.itinerary.kota.edit', $city) }}" class="btn btn-sm btn-warning">Edit</a>

<form action="{{ route('admin.itinerary.kota.destroy', $city) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kota ini?')">
        Hapus
    </button>
</form>


              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="mt-3">
        {{ $cities->links() }}
      </div>
    </div>
  </div>

</div>
@endsection
