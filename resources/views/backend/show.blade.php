@extends('layouts.app')
@section('content')

    <section class="py-5 bg-grey-light">
        <div class="container-fluid">
            <div class="row justify-content-center mt-4">
                <div class="col-xl-7 col-lg-7 col-md-6 p-0">
                    <h2>{{ $form->title }}</h2>
                </div>
                <form action="{{route('forms.save')}}" method="post" autocomplete="off">
                @csrf
                <div id="rendered-form"></div>
                </form>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <!-- Include scripts in correct order -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>


    <script>
        $(function () {
            // Pass PHP decoded JSON safely to JS
            const formData = @json($formJson);

            $('#rendered-form').formRender({
                formData: formData
            });
        });
    </script>
@endpush