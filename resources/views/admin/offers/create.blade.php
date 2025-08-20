<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Create New Offer</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('offers.store') }}" method="POST">
        @csrf

        <!-- Offer Name -->
        <div class="mb-4">
            <label for="name" class="block font-semibold mb-1">Offer Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <!-- Offer Type -->
        <div class="mb-4">
            <label for="type" class="block font-semibold mb-1">Offer Type</label>
            <select name="type" id="type" required
                class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Select Type --</option>
                <option value="free" {{ old('type')=='free' ? 'selected' : '' }}>Buy X Get Y Free</option>
                <option value="discount" {{ old('type')=='discount' ? 'selected' : '' }}>Buy X Get % Off</option>
            </select>
        </div>

        <!-- Parameters -->
        <div class="mb-4">
            <label for="parameters" class="block font-semibold mb-1">Offer Parameters</label>
            <small class="text-gray-600">Enter JSON format: <br>
                For Free: {"buy":2,"free":1} <br>
                For Discount: {"buy":2,"discount":20}
            </small>
            <textarea name="parameters" id="parameters" rows="4" required
                class="w-full border border-gray-300 rounded px-3 py-2">{{ old('parameters') }}</textarea>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Offer</button>
    </form>
</div>

<script>
    // Optional: dynamic placeholder for parameters based on type
    const typeSelect = document.getElementById('type');
    const paramsTextarea = document.getElementById('parameters');

    typeSelect.addEventListener('change', function() {
        if (this.value === 'free') {
            paramsTextarea.value = '{"buy":2,"free":1}';
        } else if (this.value === 'discount') {
            paramsTextarea.value = '{"buy":2,"discount":20}';
        } else {
            paramsTextarea.value = '';
        }
    });
</script>
