@extends('layouts.master')
@section('title', 'Doping')
@section('content')
    <h1 class="text-center pt-3">Doping List</h1>
    {{-- Input Pencarian --}}
    <div class="container card">
        <div class="card-body">
            <form action="/cari" method="POST">
            @csrf
                <div class="input-group">
                    <div class="container-fluid text-center">
                        <div class="row">
                            <div class="col" style="width: 40%">
                                <select id="kategori" name="kategori" class="form-control" id="" required>
                                    <option disabled selected>Pilih Kategori</option>
                                    <option value="nama_zat">Zat</option>
                                    <option value="nama_produk">Nama Produk</option>
                                    <option value="no_reg">No Registrasi</option>
                                </select>
                            </div>
                            <div class="col" style="width: 40%">
                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                {{-- <input type="text" name="cari" class="form-control"> --}}
                                <select name="cari" class="form-select select2" id="cari" style="width: 100%;">
                                    {{-- <option value="">Tampilkan Semua</option> --}}
                                </select>
                            </div>
                            <div class="col" style="width: 20%">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Info --}}
    <div class="container pt-3 text-center">
        <div class="row">
            <div class="col">
                @if ($count > 0)
                    <p>
                        Hasil pencarian ini mengandung unsur doping, <br> Untuk Konsultasi lebih lanjut harap menghubungi IPTEKOR KONI DIY
                        <a href="#">
                            <i class="fa-brands fa-lg fa-whatsapp" onclick="wa()"></i>
                        </a>
                    </p>
                @endif
            </div>  
        </div>
    </div>
    {{-- Hasil Pencarian --}}
    <div class="container pb-3">
        <div class="row">
            <div class="col">
                {{-- tabele responsiv --}}
                <div class="table-responsive">
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Zat</th>
                                <th>No Registrasi</th>
                                <th>Nama produk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count == 0)
                                <tr>
                                    <td colspan="5" class="text-center">Suplement/Obat Bukan Doping Atau Belum Masuk Dalam Database</td>
                                </tr>
                            @else
                                @foreach ($products as $product)
                                <tr>
                                    <th></th>
                                    <td>{{ $product->nama_zat }}</td>
                                    <td>{{ $product->no_reg }}</td>
                                    <td>{{ $product->nama_produk }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#details{{ $product->id_produk}}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($count > 0)
        @foreach ($products as $product)
            <!-- Modal -->
            <div class="modal fade" id="details{{ $product->id_produk }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Detail {{ $product->nama_produk }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body bg-dark" id="detailed">
                    <div class="row align-items-center text-center">
                        <table class="table table-striped table-dark" style="width: 100%">
                            <tr>
                                <th style="width: 20%">Bentuk</th>
                                <td>{{ $product->bentuk }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">Kemasan</th>
                                <td>{{ $product->kemasan }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">Produsen</th>
                                <td>{{ $product->produsen }}</td>
                            </tr>
                        </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mx-auto" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
        <script>
            $(document).ready(function(){ 
                var t = $('#myTable').DataTable({
                    searching: false,
                    "columnDefs": [ 
                        {"searchable": false, "orderable": false, "targets": 0},
                        // {"className": "dt-center", "targets": "_all"}
                    ],
                order: [[1, 'asc']]
                });
                t.on('order.dt search.dt', function () {
                    let i = 1;
                    t.cells(null, 0, { search: 'applied', order: 'applied' })
                    .every(function (cell) {
                        this.data(i++);
                    });
                })
                .draw();
            });
        </script>
    @endif
    <script>
        // Set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        // Sereach Dropdown Data
        // new Choices(document.querySelector(".choices-single"));
        $('#cari').select2();
        $('#kategori').on('change', function(e) {
            e.preventDefault();
            var kategori = $('#kategori').find(":selected").val();
            $.ajax({
                url:'/carix/' + kategori,
                type:'get',
                // data:{
                //     kategoris: $('#kategori').find(":selected").val()
                // },
                
                success:  function (data) {
                    // console.log(data.results[1].);
                    // alert(data);
                    //HERE WOULD GO THE LOGIC TO PLACE THE INFORMATION IN YOUR FORM from the response
                    var select = document.getElementById('cari');
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = 'Tampilkan Semua';
                    select.appendChild(opt);
                    for (let i = 0; i < data.results.length; i++) {
                        if (kategori == "nama_produk") {
                            select.add(new Option(data.results[i].nama_produk));
                        }else if (kategori == "no_reg") {
                            select.add(new Option(data.results[i].no_reg));
                        }else if (kategori == "nama_zat") {
                            select.add(new Option(data.results[i].nama_zat));
                        }
                    }
                    $('#kategori').on('change', function(e) {
                        e.preventDefault();
                        var select = document.getElementById("cari");
                        var length = select.length;
                        for (i = 0; i < length; i++) {
                            select.remove(0);
                        //  or
                        //  select.options[0] = null;
                        } 
                    })
                },
                error:function(x,xs,xt){
                console.log('error: ' + JSON.stringify(x) +"\n error string: "+ xs + "\n error throwed: " + xt);
                }
            });
        })
    </script>
@endsection
