@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h3 class="text-light mb-0">Manajemen Wisata Kuliner</h3>
        <button class="btn btn-light ms-auto me-0" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi-plus-circle me-2"></i>Tambah Wisata Kuliner Baru
        </button>
    </div>
    
      <div class="card-body" id="show_all">
        <h1 class="text-center text-secondary my-5">Loading...</h1>
      </div>
    </div>
  </div>
</div>
{{-- add new modal start --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Wisata Kuliner Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
            <div class="my-2">
              <label for="foto_wisata_kuliner">Foto Wisata Kuliner</label>
              <input type="file" name="foto_wisata_kuliner" class="form-control" required>
            </div>
          <div class="my-2">
            <label for="nama_wisata_kuliner">Nama Wisata Kuliner</label>
            <input type="text" name="nama_wisata_kuliner" class="form-control" placeholder="Nama Wisata Kuliner" required>
          </div>
          <div class="my-2">
            <label for="alamat">Alamat Wisata Kuliner</label>
            <input type="text" name="alamat" class="form-control" placeholder="Alamat Wisata Kuliner" required>
          </div>
          <div class="my-2">
            <label for="latitude">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude" required>
          </div>
          <div class="my-2">
            <label for="longitude">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Longitude" required>
          </div>
          <div class="my-2">
            <label for="showMaps">Map</label>
                            <div id="showMaps" class="form-control" style="height: 380px;">

                            </div>
          </div>
          <div class="my-2">
            <label for="id_kategori">Jenis Kategori</label>
            <select name="id_kategori" class="form-control" required>
                <option value="" selected disabled>Pilih Jenis Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->jenis_wisata_kuliner }}</option>
                @endforeach
            </select>
          </div>
          <?php if (Auth::user()->role == "admin") : ?>
          <div class="my-2">
            <label for="id_user">Pemilik</label>
            <select name="id_user" class="form-control" required>
                <option value="" selected disabled>Pilih Pemilik</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
          <?php elseif (Auth::user()->role == "owner") : ?>
          <input type="hidden" name="id_user" value="{{Auth::user()->id}}">
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" id="add_btn" class="btn btn-primary">Tambah Wisata Kuliner</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add new modal end --}}

{{-- edit modal start --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Wisata Kuliner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="old_foto_wisata_kuliner" id="old_foto_wisata_kuliner">
        <div class="modal-body p-4 bg-light">
            <div class="my-2">
                <label for="foto_wisata_kuliner">Foto Wisata Kuliner</label>
                <input type="file" name="foto_wisata_kuliner" class="form-control">
              </div>
              <div class="mt-2" id="foto_wisata_kuliner">
                  
            </div>
            <div class="my-2">
              <label for="nama_wisata_kuliner">Nama Wisata Kuliner</label>
              <input type="text" name="nama_wisata_kuliner" id="nama_wisata_kuliner" class="form-control" placeholder="Nama Wisata Kuliner" required>
            </div>
            <div class="my-2">
                <label for="alamat">Alamat Wisata Kuliner</label>
                <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat Wisata Kuliner" required>
              </div>
              <div class="my-2">
                <label for="editLatitude">Latitude</label>
                <input type="text" name="editLatitude" id="editLatitude" class="form-control" placeholder="Latitude" required>
              </div>
              <div class="my-2">
                <label for="editLongitude">Longitude</label>
                <input type="text" name="editLongitude" id="editLongitude" class="form-control" placeholder="Longitude" required>
              </div>
              <div class="my-2">
                <label for="editMaps">Map</label>
                <div id="editMaps" class="form-control" style="height: 380px;"></div>
              </div>
              <div class="my-2">
                <label for="id_kategori">Jenis Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="" selected disabled>Pilih Jenis Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->jenis_wisata_kuliner }}</option>
                    @endforeach
                </select>
              </div>
              <?php if (Auth::user()->role == "admin") : ?>
              <div class="my-2">
                <label for="id_user">Pemilik</label>
                <select name="id_user" class="form-control" required>
                  <option value="" selected disabled>Pilih Pemilik</option>
                  @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                  @endforeach
              </select>
              </div>
              <?php elseif (Auth::user()->role == "owner") : ?>
              <input type="hidden" name="id_user" id="id_user">
              <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" id="edit_btn" class="btn btn-success">Update Wisata Kuliner</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit modal end --}}
  <script>
  var map, editMap;
  var marker, editMarker;

  function gmapsCallback() {
    initAddMap();
    initEditMap();
  }

  function initAddMap() {
    map = new google.maps.Map(document.getElementById("showMaps"), {
      center: { lat: -7.008128, lng: 113.859160 },
      zoom: 16
    });
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var initLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.setCenter(initLocation);
      });
    }
    mapClickListener(map, "latitude", "longitude", "add");
  }

  function initEditMap() {
    editMap = new google.maps.Map(document.getElementById("editMaps"), {
      center: { lat: -7.008128, lng: 113.859160 },
      zoom: 16
    });
    mapClickListener(editMap, "editLatitude", "editLongitude", "edit");
  }

  function mapClickListener(mapInstance, latInput, lngInput, type) {
    google.maps.event.addListener(mapInstance, 'click', function (event) {
      placeMarker(mapInstance, event.latLng, type);
      document.getElementById(latInput).value = event.latLng.lat();
      document.getElementById(lngInput).value = event.latLng.lng();
    });
  }

  function placeMarker(mapInstance, location, type) {
    if (type === "add") {
      if (marker) {
        marker.setPosition(location);
      } else {
        marker = new google.maps.Marker({
          position: location,
          map: mapInstance
        });
      }
    } else {
      if (editMarker) {
        editMarker.setPosition(location);
      } else {
        editMarker = new google.maps.Marker({
          position: location,
          map: mapInstance
        });
      }
    }
    mapInstance.setCenter(location);
  }
  $(function() {
    // add new ajax request
    $("#add_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_btn").text('Adding...');
        $.ajax({
            url: '{{ route('wisataKulinerStore') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    Swal.fire(
                        'Berhasil!',
                        'Wisata Kuliner berhasil ditambahkan!',
                        'success'
                    )
                    fetchAll();
                }
                $("#add_btn").text('Tambah Wisata Kuliner');
                $("#add_form")[0].reset();
                $("#addModal").modal('hide');
            }
        });
    });

    // edit ajax request
    $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{ route('wisataKulinerEdit') }}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $("#id").val(response.id);
                $("#old_foto_wisata_kuliner").val(response.foto_wisata_kuliner);
                $("#foto_wisata_kuliner").html(
                    `<img src="storage/wisata_kuliner/${response.foto_wisata_kuliner}" width="300px" class="img-fluid img-thumbnail">`);
                $("#nama_wisata_kuliner").val(response.nama_wisata_kuliner);
                $("#alamat").val(response.alamat);
                $("#editLatitude").val(response.latitude);
                $("#editLongitude").val(response.longitude);

                // Set the selected value for id_kategori
                $('#id_kategori').val(response.id_kategori);

                // Ensure the correct option is selected
                $('#id_kategori option').each(function() {
                    if ($(this).val() == response.id_kategori) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });

                // Set the selected value for id_user if admin
                @if (Auth::user()->role == "admin")
                $('#id_user').val(response.id_user);
                $('#id_user option').each(function() {
                    if ($(this).val() == response.id_user) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });
                @elseif (Auth::user()->role == "owner")
                $("#id_user").val(response.id_user);
                @endif

                // Update the map marker
                var latLng = new google.maps.LatLng(response.latitude, response.longitude);
                placeMarker(editMap, latLng, "edit");
                editMap.setCenter(latLng);
            }
        });
    });

    // update ajax request
    $("#edit_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Updating...');
        $.ajax({
            url: '{{ route('wisataKulinerUpdate') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    Swal.fire(
                        'Berhasil!',
                        'Wisata Kuliner berhasil diedit!',
                        'success'
                    )
                    fetchAll();
                }
                $("#edit_btn").text('Update Wisata Kuliner');
                $("#edit_form")[0].reset();
                $("#editModal").modal('hide');
            }
        });
    });

    // delete ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak bisa mengembalikan data ini jika menghapusnya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, saya yakin!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('wisataKulinerDelete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            'Berhasil!',
                            'Wisata Kuliner berhasil dihapus!',
                            'success'
                        )
                        fetchAll();
                    }
                });
            }
        })
    });

    // fetch all ajax request
    fetchAll();

    function fetchAll() {
        $.ajax({
            url: '{{ route('wisataKulinerFetchAll') }}',
            method: 'get',
            success: function(response) {
                $("#show_all").html(response);
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable("#datatable")) {
                  $('#datatable').DataTable().clear().destroy();
                }
                // Initialize DataTable
                $("#datatable").DataTable({
                    order: [0, 'asc']
                });
            }
        });
    }
});

  </script>
  <!-- Google Maps API -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=gmapsCallback"></script>
  <!-- /.row (main row) -->
@endsection
