<div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Animals</h1>
    <ul class="space-y-2">
        @foreach($animals as $a)
            <li class="bg-white rounded-xl border p-3 flex justify-between">
                <span>{{ $a->name }} — {{ ucfirst($a->species) }}</span>
                <span class="text-sm text-gray-600">{{ ucfirst($a->status) }}</span>
            </li>
        @endforeach
    </ul>
</div>
