@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Rumah Sakit</h2>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form tambah rumah sakit --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Rumah Sakit</div>
        <div class="card-body">
            <form action="{{ url('/rumahsakit') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="nama_rumah_sakit" class="form-control" placeholder="Nama Rumah Sakit" required>
                    </div>
                    <div class="col">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col">
                        <input type="text" name="telepon" class="form-control" placeholder="Telepon" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel rumah sakit --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Rumah Sakit</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $rs)
            <tr id="row-{{ $rs->id }}">
                <td>{{ $i+1 }}</td>
                <td>{{ $rs->nama_rumah_sakit }}</td>
                <td>{{ $rs->alamat }}</td>
                <td>{{ $rs->email }}</td>
                <td>{{ $rs->telepon }}</td>
                <td>
                    {{-- Tombol edit pakai data-* --}}
                    <a href="#"
                       class="btn btn-sm btn-warning btn-edit"
                       data-id="{{ $rs->id }}"
                       data-nama="{{ $rs->nama_rumah_sakit }}"
                       data-alamat="{{ $rs->alamat }}"
                       data-email="{{ $rs->email }}"
                       data-telepon="{{ $rs->telepon }}">
                       Edit
                    </a>

                    {{-- Tombol hapus pakai Ajax --}}
                    <button class="btn btn-sm btn-danger" onclick="deleteRS({{ $rs->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Rumah Sakit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="editForm">
              @csrf
              @method('PUT')
              <input type="hidden" id="edit_id">

              <div class="mb-3">
                <label class="form-label">Nama Rumah Sakit</label>
                <input type="text" id="edit_nama" name="nama_rumah_sakit" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" id="edit_alamat" name="alamat" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" id="edit_email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Telepon</label>
                <input type="text" id="edit_telepon" name="telepon" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

{{-- Script Ajax --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function deleteRS(id) {
    if(confirm("Yakin ingin menghapus data ini?")) {
        $.ajax({
            url: "{{ url('rumahsakit') }}/" + id,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": "DELETE"
            },
            success: function(response) {
                if(response.success) {
                    $("#row-" + id).remove();
                }
            },
            error: function(xhr) {
                alert("Gagal menghapus: " + xhr.responseText);
            }
        });
    }
}

$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();

    let id = $(this).data('id');
    let nama = $(this).data('nama');
    let alamat = $(this).data('alamat');
    let email = $(this).data('email');
    let telepon = $(this).data('telepon');

    $('#edit_id').val(id);
    $('#edit_nama').val(nama);
    $('#edit_alamat').val(alamat);
    $('#edit_email').val(email);
    $('#edit_telepon').val(telepon);

    $('#editModal').modal('show');
});

$('#editForm').submit(function(e) {
    e.preventDefault();

    let id = $('#edit_id').val();

    $.ajax({
        url: "{{ url('rumahsakit') }}/" + id,
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": "PUT",
            "nama_rumah_sakit": $('#edit_nama').val(),
            "alamat": $('#edit_alamat').val(),
            "email": $('#edit_email').val(),
            "telepon": $('#edit_telepon').val()
        },
        success: function(response) {
            if(response.success){
                location.reload();
            }
        },
        error: function(xhr) {
            alert("Gagal update: " + xhr.responseText);
        }
    });
});
</script>
@endsection
