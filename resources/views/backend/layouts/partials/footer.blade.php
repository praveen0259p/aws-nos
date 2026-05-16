<script src="{{ asset('backend/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{asset('backend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('backend/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('backend/js/dataTables.js')}}"></script>
<script src="{{asset('backend/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('backend/js/sweetalert2.js')}}"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
$(document).ready(function() {
    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Only letters and space are allowed");
    jQuery.validator.addMethod("imageAndSize", function(value, element) {
        if (this.optional(element)) {
            return true; // allow empty if not required
        }
        var file = element.files[0];
        if (!file) return false;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        var maxSize = 5 * 1024 * 1024;
        if (!allowedExtensions.test(file.name)) {
            return false;
        }
        if (file.size > maxSize) {
            return false;
        }
        return true;
    }, "Please upload a valid Jpg,jpeg,png image under 5MB.");

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.validator.addMethod("alphanumericSpecial", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s.\-_]+$/.test(value);
    }, "Only letters, numbers, spaces, dots, hyphens, and underscores are allowed");
    jQuery.validator.addMethod("pdfAndSize", function(value, element) {
        if (this.optional(element)) {
            return true;
        }
        var file = element.files[0];
        if (!file) return false;
        var allowedExtensions = /(\.pdf)$/i;
        var maxSize = 10 * 1024 * 1024;
        if (!allowedExtensions.test(file.name)) {
            return false;
        }
        if (file.size > maxSize) {
            return false;
        }
        return true;
    }, "Please upload a valid PDF file under 10MB.");
    const $body = $('body');
    const $toggleBtn = $('.main-menu');
    /* ===============================
        SUBMENU TOGGLE (ACCORDION)
    =============================== */
    $('.submenu-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if ($body.hasClass('sidebar-collapsed')) return;

        const $btn = $(this);
        const expanded = $btn.attr('aria-expanded') === 'true';
        const $submenu = $btn.next('.submenu');

        /* 🔹 SAME LEVEL ke submenus close karo */
        $btn
            .closest('.submenu, .side-menu')
            .find('> .menu-item > .submenu-toggle')
            .not($btn)
            .attr('aria-expanded', 'false')
            .find('.bi-chevron-down')
            .removeClass('rotate')
            .end()
            .next('.submenu')
            .removeClass('show');

        /* 🔹 CURRENT submenu toggle */
        $btn.attr('aria-expanded', !expanded);
        $submenu.toggleClass('show', !expanded);
        $btn.find('.bi-chevron-down').toggleClass('rotate', !expanded);
    });

    /* ===============================
        SIDEBAR TOGGLE (DESKTOP + MOBILE)
    =============================== */
    if ($toggleBtn.length) {
        $toggleBtn.on('click', function(e) {
            e.stopPropagation();

            const isMobile = $(window).width() <= 992;

            if (isMobile) {
                /* MOBILE – OFFCANVAS */
                $body.toggleClass('sidebar-open');
                $(this).attr('aria-expanded', $body.hasClass('sidebar-open'));

            } else {
                /* DESKTOP – COLLAPSE */
                $body.toggleClass('sidebar-collapsed');
                const isCollapsed = $body.hasClass('sidebar-collapsed');

                $(this).attr('aria-expanded', !isCollapsed);

                /* Close all submenus & reset arrows when collapsed */
                if (isCollapsed) {
                    $('.submenu').removeClass('show');
                    $('.submenu-toggle')
                        .attr('aria-expanded', 'false')
                        .find('.bi-chevron-down')
                        .removeClass('rotate');
                }
            }
        });
    }

    /* ===============================
        CLOSE SIDEBAR ON OUTSIDE CLICK (MOBILE)
    =============================== */
    $(document).on('click', function(e) {
        if (
            $body.hasClass('sidebar-open') &&
            !$(e.target).closest('.side-navigation').length &&
            !$(e.target).closest('.main-menu').length
        ) {
            $body.removeClass('sidebar-open');
            $toggleBtn.attr('aria-expanded', 'false');
        }
    });

});
</script>
@stack('scripts')
</body>

</html>