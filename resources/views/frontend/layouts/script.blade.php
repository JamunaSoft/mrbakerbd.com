
    <script src="{{asset('frontend/js/core.min.js')}}"></script>
    <script src="{{asset('frontend/js/script.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.querySelector('[data-rd-navbar-toggle=".user-dropdown"]');
            const dropdown = document.querySelector('.user-dropdown');
            if (toggleBtn && dropdown) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                document.addEventListener('click', function (e) {
                    if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
    @include('frontend.layouts.marketing-events')
    @include('tracking.consent')
    @yield('page-scripts')
