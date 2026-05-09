@extends('layouts.app')
@section('title', 'Guidelines')
@section('content')
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
<section class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form class="px-1 filter-tools" role="search">
                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-md-6 text-end">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <label class="input-group-text rounded-start-1 bg-white border-0"
                                    for="sortby"><i class="bi bi-filter-left text-lightpurple"
                                        aria-hidden="true"></i></label>
                                <select class="form-select rounded-end-1 py-2 border-0" id="sortby">
                                    <option selected disabled>Sort By</option>
                                    <option value="DESC">Latest</option>
                                    <option value="ASC">Old</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <label class="input-group-text rounded-start-1 bg-white border-0"
                                    for="perpage"><i class="bi bi-journal-text text-lightpurple"
                                        aria-hidden="true"></i></label>
                                <select class="form-select rounded-end-1 py-2 border-0" id="perpage">
                                    <option value="10">10 Per Page</option>
                                    <option value="20">20 Per Page</option>
                                    <option value="30">30 Per Page</option>
                                </select>
                            </div>
                        </div>
                         <div class="offset-xl-4 col-xl-3 offset-lg-3 col-lg-3 col-md-6">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <span class="input-group-text rounded-start-1 bg-white border-0"><i
                                        class="bi bi-search text-lightpurple" aria-hidden="true"></i></span>
                                <input id="custom_search" type="search" class="form-control rounded-end-1 py-2 border-0"
                                    placeholder="Search" aria-label="Search" aria-describedby="search">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive custom-table-wrapper">
                    <table id="documents-table" class="table custom-table mb-0 px-1">
                        <thead class="mb-2">
                            <tr class="mb-2">
                                <th scope="col">S.No</th>
                                <th scope="col">Title</th>
                                <th scope="col">Published Date</th>
                                <th scope="col">Size</th>
                                <th scope="col">View</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div class="row mt-3">
            <div class="col-xxl-10 col-lg-9 col-md-8">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center gap-2">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&#8249;</span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&#8250;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div> -->
    </div>
</section>
@endsection
@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        const currentRoute = "/{{ request()->path() }}";
        var table= $('#documents-table').DataTable({
            processing: false,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: parseInt($('#perpage').val()),
            ajax: {
                url: '{{ route("yajraData") }}',
                data: function(d) {
                    d.perpage = $('#perpage').val();
                    d.search = $('#custom_search').val();
                    d.sortby = $('#sortby').val();
                    d.route   = currentRoute;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false
                },
                {
                    data: 'published_date',
                    name: 'created_at',
                    orderable: false
                },
                {
                    data: 'size',
                    name: 'asset.size',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            pagingType: "simple_numbers",
            language: {
                paginate: {
                    previous: "&#8249;",
                    next: "&#8250;"   
                }
            }
        });
        $('#custom_search').on('keyup', function() {
            table.ajax.reload();
        });
        $('#perpage, #sortby').change(function() {
            table.ajax.reload();
        });
        $('#perpage').change(function() {
            table.page.len($(this).val()).draw();
        });
    });
</script>
@endpush