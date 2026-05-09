@extends('layouts.app')
@section('title', 'Login')
@section('content')
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
<section class="py-5 bg-grey-light">
    <div class="container-fluid">
        @foreach (getMenu() as $menu)
            <li><a href="{{ url($menu->url) }}" class="text-blue text-decoration-none d-block">
            {{ $menu->title }}</a></li>
        @endforeach
    </div>
</section>
@endsection
