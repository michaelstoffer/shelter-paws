<script setup>
import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const animals = ref(page.props.animals || [])
const filters = ref(page.props.filters || { status: '' })

function bump(animal, delta = -1) {
    // Decrease by 1 to raise priority (1 is highest)
    const next = Math.min(10, Math.max(1, (animal.priority ?? 3) + delta))
    router.patch(route('animals.priority.update', animal.id), { priority: next }, {
        preserveScroll: true,
        onSuccess: () => {
            animal.priority = next
            // resort locally to reflect change
            animals.value.sort((a,b) => a.priority - b.priority || (a.name > b.name ? 1 : -1))
        }
    })
}

function filterByStatus() {
    router.get(route('adoption.queue'), { status: filters.value.status }, {
        preserveState: true,
        replace: true,
    })
}
</script>
<template>
    <div class="max-w-5xl mx-auto p-6">
        <header class="mb-6 flex flex-wrap items-end gap-3">
            <h1 class="text-2xl font-semibold">Adoption Queue</h1>
            <div class="ml-auto flex items-center gap-2">
                <label class="text-sm text-gray-600">Status:</label>
                <select class="border rounded-lg px-3 py-1.5 text-sm"
                        v-model="filters.status"
                        @change="filterByStatus">
                    <option value="">All</option>
                    <option value="available">Available</option>
                    <option value="hold">Hold</option>
                    <option value="pending">Pending</option>
                    <option value="adopted">Adopted</option>
                </select>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow divide-y">
            <div v-for="a in animals" :key="a.id" class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-medium">{{ a.name }} <span class="text-gray-400">— {{ a.species }}</span></div>
                    <div class="text-xs text-gray-600">Status: <span class="capitalize">{{ a.status }}</span></div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-sm">
                        Priority: <span class="font-semibold">{{ a.priority }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-sm"
                                @click="bump(a, -1)">Bump ↑</button>
                        <button class="px-3 py-1.5 rounded-lg bg-white border text-sm"
                                @click="bump(a, +1)">Lower ↓</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
