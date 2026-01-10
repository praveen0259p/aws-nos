@extends('layouts.app')
@section('title', 'Forms')
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
                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <span class="input-group-text rounded-start-1 bg-white border-0" id="search"><i
                                        class="bi bi-search text-lightpurple" aria-hidden="true"></i></span>
                                <input type="search" class="form-control rounded-end-1 py-2 border-0"
                                    placeholder="Search" aria-label="Search" aria-describedby="search">
                            </div>
                        </div>
                        <div class="offset-xl-2 col-xl-2 col-lg-2 col-md-6 text-end">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <label class="input-group-text rounded-start-1 bg-white border-0"
                                    for="sortby"><i class="bi bi-filter-left text-lightpurple"
                                        aria-hidden="true"></i></label>
                                <select class="form-select rounded-end-1 py-2 border-0" id="sortby">
                                    <option selected disabled>Sort By</option>
                                    <option value="latest">Latest</option>
                                    <option value="old">Old</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <label class="input-group-text rounded-start-1 bg-white border-0"
                                    for="category"><i class="bi bi-filter-left text-lightpurple"
                                        aria-hidden="true"></i></label>
                                <select class="form-select rounded-end-1 py-2 border-0" id="category">
                                    <option selected disabled>Category</option>
                                    <option value="annual reports">Annual Reports</option>
                                    <option value="Annual Reports on Prevention Atrocity Act (PoA)">Annual
                                        Reports on Prevention Atrocity Act (PoA)</option>
                                    <option value="Annual Reports on Protection of Civil Rights (PCR)">Annual
                                        Reports on Protection of Civil Rights (PCR)</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4">
                            <div class="input-group border-lightpurple rounded-1 mb-3">
                                <label class="input-group-text rounded-start-1 bg-white border-0"
                                    for="perpage"><i class="bi bi-journal-text text-lightpurple"
                                        aria-hidden="true"></i></label>
                                <select class="form-select rounded-end-1 py-2 border-0" id="perpage">
                                    <option selected>10 Per Page</option>
                                    <option value="15 Per Page">15 Per Page</option>
                                    <option value="20 Per Page">20 Per Page</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2">
                            <button type="submit" class="clear-btn rounded-1"><i
                                    class="bi bi-x me-1 text-lightpurple" aria-hidden="true"></i> Clear</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive custom-table-wrapper">
                    <table class="table custom-table mb-0 px-1">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col" colspan="2">Title</th>
                                <th scope="col">Published Date</th>
                                <th scope="col">Size</th>
                                <th scope="col">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($forms as $index => $document)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td colspan="2">
                                    <div class="d-flex gap-1">
                                        <i class="bi bi-copy me-2 text-blue" aria-hidden="true"></i>
                                        {{ $document->title }}
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($document->created_at)->format('d.m.Y') }}
                                </td>
                                <td>
                                    {{ $document->asset->size_mb }} MB
                                </td>
                                <td>
                                    <a href="{{ asset($document->asset->url) }}" target="_blank" class="view-btn">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-3">
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
            <div class="col-xxl-2 col-lg-3  col-md-4 text-center text-md-end mt-md-0 mt-3">
                <a href="#" class="white-btn text-uppercase d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#162f6a">
                        <path
                            d="m480-240 160-160-56-56-64 64v-168h-80v168l-64-64-56 56 160 160ZM200-640v440h560v-440H200Zm0 520q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v499q0 33-23.5 56.5T760-120H200Zm16-600h528l-34-40H250l-34 40Zm264 300Z" />
                    </svg> View Archive
                </a>
            </div>
        </div>
    </div>
</section>
@endsection