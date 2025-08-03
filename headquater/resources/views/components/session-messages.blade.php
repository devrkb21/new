@if (session('success'))
    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 rounded-md p-4" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-400 rounded-md p-4" role="alert">
        {{ session('error') }}
    </div>
@endif