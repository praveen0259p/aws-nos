@extends('backend.layouts.app')
@section('title', 'menus')
@section('content')

<div class="container-fluid">
   <div class="row py-2">
      <div class="col-xl-8 col-md-8 col-12">
         <h1>Menu Management</h1>
      </div>
      <div class="col-xl-4 col-md-4 col-12">
         <a href="{{route('menus.create')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
      </div>
   </div>
   @if(session('success'))
   <div class="alert alert-success">
      {{ session('success') }}
   </div>
   @endif
   <form method="get" action="{{ url()->current() }}">
      <div class="row align-items-end justify-content-center">
         <div class="col-lg-3 col-md-6 col-12 mb-3">
            <x-select-input
               name="type"
               label="Select Menu Type"
               :options="\App\Models\MenuItem::menuTypeOptions()"
               placeholder="Select Menu Type"
               selected="{{request()->get('type')}}" />
         </div>
         <div class="col-lg-3 col-md-6 col-12 mb-3">
            <x-select-input
               name="status"
               label="Select Status"
               :options="statusoptions()"
               placeholder="Select Status"
               selected="{{request()->get('status')}}" />
         </div>
         <div class="col-lg-3 col-md-6 col-12 mb-3">
            <x-select-input
               name="page_type"
               label="Select Page Type"
               :options="pagetype()"
               placeholder="Select Page Type"
               selected="{{request()->get('page_type')}}" />
         </div>
         <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="d-flex align-items-center gap-2">
               <button type="submit" href="{{route('menus.list')}}" class="theme-btn rounded-2 py-2 px-4">Search</button>
               <a href="{{ route('menus.list') }}" class="border-btn rounded-2 py-2 px-4">Reset</a>
            </div>
         </div>
      </div>
   </form>
   <div class="row py-2">
      <div class="col-12">
         <div class="table-responsive">
            <table class="table table-bordered table-data">
               <thead>
                  <tr>
                     <th scope="col">S.No</th>
                     <th scope="col">Title</th>
                     <th scope="col">Children</th>
                     <th scope="col">Target</th>
                     <th scope="col">Menu Type</th>
                     <th scope="col">Page Type</th>
                     <th scope="col">Sorting</th>
                     <th scope="col">Created By</th>
                     <th scope="col">Created Date</th>
                     <th scope="col">Is Active</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($menu as $index => $menu)
                  <tr>
                     <th scope="row" class="text-end">{{ $index + 1 }}</th>
                     <td>{{ $menu->title ?? '—' }}</td>
                     <td>
                        @if($menu->childrenRecursive->count())
                        <a href="{{ route('menus.child.list',encrypt($menu->id)) }}">
                           <span class="bg-blue rounded-2 badge fs-6">{{ $menu->childrenRecursive->count() ?? '—' }}</span>
                        </a>
                        @else
                        <span class="bg-blue rounded-2 badge fs-6">{{ $menu->childrenRecursive->count() ?? '—' }}</span>
                        @endif
                     </td>
                     <td>{{ $menu->target ?? '—' }}</td>
                     <td>{{ $menu->menu_type_label }}</td>
                     <td> {{ $menu->page_type_label }}</td>
                     <td> {{ $menu->order_index}}</td>
                     <td>{{ optional($menu->user)->full_name ?? '—' }}
                        ({{ optional($menu->user->role)->name ?? '—' }})
                     </td>
                     <td>{{ $menu->created_at ?? '—' }}</td>
                     <td class="text-center">
                        <div class="form-check form-switch">
                           <input class="form-check-input status-change pointer" role="button"
                              type="checkbox" data-id="{{$menu->id}}" data-active="{{$menu->active}}"
                              {{ $menu->active ? 'checked' : '' }}>
                        </div>
                     </td>
                     <td>
                        <ul class="d-flex align-items-center justify-content-center gap-3">
                           <li>
                              <a href="{{ route('menus.edit', encrypt($menu->id)) }}" class="fs-5 edit">
                                 <i class="bi bi-pencil-square"></i>
                              </a>
                           </li>
                           <li>
                              <button type="submit" class="btn p-0 fs-5 delete delete-btn" data-id="{{$menu->id}}">
                                 <i class="bi bi-trash3"></i>
                              </button>
                           </li>
                        </ul>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@endsection
@push('scripts')
<script>
   (()=>{
      let table = new DataTable('.table-data');
      $(document).on('click', '.status-change', function(e) {
         e.preventDefault();
         let checkbox = $(this);
         let id = checkbox.data('id');
         let active = checkbox.data('active');
         Swal.fire({
            title: 'Change status?',
            text: active == 1 ?
               'Do you want to deactivate this item?' : 'Do you want to activate this item?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
         }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
               url: "{{ route('menus.status') }}",
               type: "POST",
               data: {
                  id: id,
                  status: active == 1 ? 0 : 1
               },
               beforeSend: function() {
                  checkbox.prop('disabled', true);
               },
               success: function(response) {
                  if (response.success) {
                     checkbox.data('active', response.status);
                     checkbox.prop('checked', response.status == 1);
                     Swal.fire(
                        'Updated!',
                        response.message,
                        'success'
                     );
                  }
               },
               error: function(res) {
                  Swal.fire(
                     'Error!',
                     res.responseJSON.message,
                     'error'
                  );
               },
               complete: function() {
                  checkbox.prop('disabled', false);
               }
            });
         });
      });

      $(document).on('click', '.delete-btn', function(e) {
         e.preventDefault();
         let button = $(this);
         let id = button.data('id');

         Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
         }).then((result) => {
            if (result.isConfirmed) {
               $.ajax({
                  url: "{{ route('menus.delete') }}",
                  type: "POST",
                  data: {
                     id: id,
                  },
                  beforeSend: function() {
                     button.prop('disabled', true);
                  },
                  success: function(response) {
                     if (response.success) {
                        button.closest('tr').remove();
                        Swal.fire(
                           'Deleted!',
                           response.message,
                           'success'
                        );
                     } else {
                        Swal.fire(
                           'Error!',
                           response.message,
                           'error'
                        );
                     }
                  },
                  error: function(err) {
                     let errorMessage = 'Something went wrong!';
                     if (err.responseJSON && err.responseJSON.message) {
                        errorMessage = err.responseJSON.message;
                     }
                     Swal.fire(
                        'Error!',
                        errorMessage,
                        'error'
                     );
                  },
                  complete: function() {
                     button.prop('disabled', false);
                  }
               });
            }
         });
      });
   })();
</script>
@endpush