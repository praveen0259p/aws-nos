<footer class="py-5 bg-blue text-white footer">
    <div class="container-fluid">
        <div class="row align-items-start">
            <div class="col-md-8">
                <h5 class="mb-3 fw-bold text-white">USEFUL LINKS</h5>
                <ul class="d-grid gap-3">
                    @foreach (getMenu()->where('is_footer', 1) as $menu)
                        <li><a href="{{ url($menu->url) }}" class="text-white text-decoration-none d-block"><i class="bi bi-chevron-right"></i> {{ $menu->title }}</a></li>
                    @endforeach
                    <!-- <li><a href="#" class="text-white text-decoration-none d-block"><i
                                class="bi bi-chevron-right"></i> Website Policies</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block"><i
                                class="bi bi-chevron-right"></i> Related Links</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block"><i
                                class="bi bi-chevron-right"></i> Sitemap</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block"><i
                                class="bi bi-chevron-right"></i> Help</a></li> -->
                </ul>
                <p class="mt-4 mb-0">
                    This Website belong to Department of Social Justice and Empowerment
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <h5 class="mb-3 fw-bold text-white">SUBSCRIBE FOR UPDATES</h5>
                <div class="d-flex gap-3 justify-content-md-end justify-content-start mb-3 social-icon">
                    <a href="https://www.facebook.com/goimsje" target="_blank"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                    <a href="https://x.com/msjegoi" target="_blank"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/msjegoi/" target="_blank"><i class="bi bi-instagram" aria-hidden="true"></i></a>
                    <a href="https://www.youtube.com/@ministryofsocialjustice511" target="_blank"><i class="bi bi-youtube" aria-hidden="true"></i></a>
                </div>
                <div class="d-flex gap-3 justify-content-md-end justify-content-start mb-3 logo-images">
                    <img src="{{asset('images/mygovmerisarkar.jpg')}}" alt="MyGov Logo">
                    <img src="{{asset('images/indiaportal.svg')}}" alt="India Gov Logo">
                </div>@php $latest = lastUpdated();@endphp
                <p class="mb-0">Last Updated On: <strong>{{ $latest['updated_at'] }}</strong></p>
            </div>

        </div>
    </div>
</footer>