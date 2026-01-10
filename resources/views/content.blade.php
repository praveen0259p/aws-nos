@extends('layouts.app')
@section('title', Route::currentRouteName())
@section('content')
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
    @if($menu && $menu->content)
        {!! $menu->content !!}
    @else
        <p class="text-center text-muted">Content not available.</p>
    @endif
@endsection