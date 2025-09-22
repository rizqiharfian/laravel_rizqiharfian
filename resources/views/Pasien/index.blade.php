@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Pasien</h2>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form tambah pasien --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Pasien</div>
        <div class="card-body">
            <form action="{{ url('/pasien') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="nama_pasien" class="form-control" placeholder="Nama Pasien" required>
                    </div>
                    <div class="col">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="no_telpon" class="form-control" placeholder="No Telepon" required>
                    </div>
                    <div class="col">
                        <select name="rumah_sakit_id" class="form-control" required>
                            <option value="">-- Pilih Rumah Sakit --</option>
                            @foreach($rs as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_rumah_sakit }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Filter pasien --}}
    <div class="mb-3">
        <label><b>Filter berdasarkan Rumah Sakit:</b></label>
        <select id="filterRs" class="form-control" style="width: 300px; display:inline-block;">
            <option value="">-- Semua Rumah Sakit --</option>
            @foreach($rs as $r)
                <option value="{{ $r->id }}">{{ $r->nama_rumah_sakit }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabel pasien --}}
    <table class="table table-bordered" id="tablePasien">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Rumah Sakit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasiens as $i => $ps)
            <tr id="row-{{ $ps->id }}">
                <td>{{ $i+1 }}</td>
                <td>{{ $ps->nama_pasien }}</td>
                <td>{{ $ps->alamat }}</td>
                <td>{{ $ps->no_telpon }}</td>
                <td>{{ $ps->rumahSakit->nama_rumah_sakit ?? '-' }}</td>
                <td>
                    <a href="#"
                       class="btn btn-sm btn-warning btn-edit"
                       data-id="{{ $ps->id }}"
                       data-nama_pasien="{{ $ps->nama_pasien }}"
                       data-alamat="{{ $ps->alamat }}"
                       data-no_telpon="{{ $ps->no_telpon }}"
                       data-rs_id="{{ $ps->rumah_sakit_id }}">
                       Edit
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="deletePasien({{ $ps->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Pasien</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="editForm">
              @csrf
              @method('PUT')
              <input type="hidden" id="edit_id">

              <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <input type="text" id="edit_nama_pasien" name="nama_pasien" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" id="edit_alamat" name="alamat" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Telepon</label>
                  <input type="text" id="edit_no_telpon" name="no_telpon" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Rumah Sakit</label>
                  <select id="edit_rs_id" name="rumah_sakit_id" class="form-control" required>
                      @foreach($rs as $r)
                          <option value="{{ $r->id }}">{{ $r->nama_rumah_sakit }}</option>
                      @endforeach
                  </select>
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
function deletePasien(id) {
    if(confirm("Yakin ingin menghapus data ini?")) {
        $.ajax({
            url: "{{ url('pasien') }}/" + id,
            type: 'POST',
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

$(document).ready(function(){

    const baseFilterUrl = "{{ url('pasien/filter') }}";
    const basePasienUrl = "{{ url('pasien') }}";

    $('#filterRs').on('change', function() {
        const rsId = $(this).val();

        if (!rsId) {
            location.href = basePasienUrl;
            return;
        }

        const url = baseFilterUrl + '/' + rsId;

        $.get(url)
         .done(function(data) {
            const tbody = $('#tablePasien tbody');
            tbody.empty();

            if (!data || data.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>');
                return;
            }

            data.forEach(function(p, i) {
                let rsName = '-';
                if (p.rumah_sakit && p.rumah_sakit.nama_rumah_sakit) {
                    rsName = p.rumah_sakit.nama_rumah_sakit;
                } else if (p.rumahSakit && p.rumahSakit.nama_rumah_sakit) {
                    rsName = p.rumahSakit.nama_rumah_sakit;
                }

                const row = `
                    <tr id="row-${p.id}">
                        <td>${i + 1}</td>
                        <td>${escapeHtml(p.nama_pasien)}</td>
                        <td>${escapeHtml(p.alamat)}</td>
                        <td>${escapeHtml(p.no_telpon)}</td>
                        <td>${escapeHtml(rsName)}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-sm btn-danger" onclick="deletePasien(${p.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
         })
         .fail(function(xhr) {
             console.error('Filter request failed', xhr);
             alert('Gagal mengambil data filter. Cek console (Network) untuk detail.');
         });
    });

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

});

$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();

    $('#edit_id').val($(this).data('id'));
    $('#edit_nama_pasien').val($(this).data('nama_pasien'));
    $('#edit_alamat').val($(this).data('alamat'));
    $('#edit_no_telpon').val($(this).data('no_telpon'));
    $('#edit_rs_id').val($(this).data('rs_id'));

    $('#editModal').modal('show');
});

$('#editForm').submit(function(e) {
    e.preventDefault();

    let id = $('#edit_id').val();

    $.ajax({
        url: "{{ url('pasien') }}/" + id,
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": "PUT",
            "nama_pasien": $('#edit_nama_pasien').val(),
            "alamat": $('#edit_alamat').val(),
            "no_telpon": $('#edit_no_telpon').val(),
            "rumah_sakit_id": $('#edit_rs_id').val()
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
