@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@push('styles')
<!-- formBuilder CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formbuilder/dist/form-builder.min.css">
@end('push');
<x-bread-crumbs current-page="{{ Route::currentRouteName() }}" :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
<section class="py-5 bg-grey-light">
  <div class="container-fluid">
    <div id="form-builder"></div>
    <button id="get-json">Get Form JSON</button>
  </div>
</section>
@endsection
@push('scripts')
  <!-- jQuery UI (REQUIRED for drag & drop) -->
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <!-- formBuilder -->
  <script src="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.js"></script>
  <script>
    $(document).ready(function () {
      const $fb = $('#form-builder').formBuilder();

      $('#get-json').click(function () {
        const json = $fb.actions.getData('json');
        console.log(json);
      });
    });
  </script>

@endpush