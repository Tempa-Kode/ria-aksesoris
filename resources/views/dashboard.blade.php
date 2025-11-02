<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

@include("partials.dashboard.head")

<body>

    @include("partials.dashboard.sidebar")

    <main class="dashboard-main">
        @include("partials.dashboard.navbar")

        <div class="dashboard-main-body">

            @include("partials.dashboard.breadcrumb")

            <div class="row gy-4">
            </div>

        </div>
        @include('partials.dashboard.footer')
    </main>
    @include('partials.dashboard.scripts')
    <script src="{{ asset('dashboard/assets/js/homeThreeChart.js') }}"></script>
</body>

</html>
