@if (!empty(session('success')))
    <script>
        document.addEventListener("DOMContentLoaded", () => showToast('✅', "{{ session('success') }}"));
    </script>
@endif

@if (!empty(session('error')))
    <script>
        document.addEventListener("DOMContentLoaded", () => showToast('⚠', "{{ session('error') }}"));
    </script>
@endif

@if (!empty(session('warning')))
    <script>
        document.addEventListener("DOMContentLoaded", () => showToast('✋', "{{ session('warning') }}"));
    </script>
@endif

@if (!empty(session('info')))
    <script>
        document.addEventListener("DOMContentLoaded", () => showToast('ℹ', "{{ session('info') }}"));
    </script>
@endif

@if (!empty(session('primary')) || !empty(session('secondary')))
    <script>
        document.addEventListener("DOMContentLoaded", () => showToast('🔔',
            "{{ session('primary') ?? session('secondary') }}"));
    </script>
@endif

{{-- Optional: Handle Laravel's default $errors validation object --}}
@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            @foreach ($errors->all() as $error)
                showToast('❌', "{{ $error }}");
            @endforeach
        });
    </script>
@endif
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button"
            class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button"
            class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
