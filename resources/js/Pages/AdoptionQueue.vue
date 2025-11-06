<!-- resources/js/Pages/AdoptionQueue.vue -->
<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue'

const page = usePage()

const animals = ref(page.props.animals || [])
const filters = ref(page.props.filters || { status: '' })

// Toast state
const toastMessage = ref('')
const toastType = ref('success')

// Per-item saving state
const saving = ref({})           // { [id]: true|false }
const bulkSaving = ref(false)    // global state for reset action

watch(() => page.props.flash, (f) => {
    if (f?.success) {
        toastMessage.value = f.success
        toastType.value = 'success'
    } else if (f?.error) {
        toastMessage.value = f.error
        toastType.value = 'error'
    } else {
        toastMessage.value = ''
    }
}, { immediate: true })

function sortLocal() {
    animals.value.sort((a,b) => (a.priority - b.priority) || a.name.localeCompare(b.name))
}

function bump(animal, delta = -1) {
    const original = animal.priority ?? 3
    const next = Math.min(10, Math.max(1, original + delta))

    // Optimistic
    animal.priority = next
    sortLocal()
    saving.value[animal.id] = true

    router.patch(`/animals/${animal.id}/priority`, { priority: next }, {
        preserveScroll: true,
        onSuccess: () => sortLocal(),
        onError: (errors) => {
            animal.priority = original
            sortLocal()
            const firstError = errors?.priority || 'Unable to update priority.'
            toastMessage.value = firstError
            toastType.value = 'error'
        },
        onFinish: () => {
            saving.value[animal.id] = false
        }
    })
}

function filterByStatus() {
    router.get('/adoption-queue', { status: filters.value.status }, {
        preserveState: true,
        replace: true,
    })
}

function resetPriorities(to = 3) {
    bulkSaving.value = true
    router.patch('/adoption-queue/reset-priorities', {
        to,
        status: filters.value.status || undefined
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Mirror server change locally
            animals.value = animals.value.map(a => ({
                ...a,
                priority: (filters.value.status ? a.status === filters.value.status : true) ? to : a.priority
            }))
            sortLocal()
        },
        onError: (errors) => {
            const msg = errors?.to || 'Unable to reset priorities.'
            toastMessage.value = msg
            toastType.value = 'error'
        },
        onFinish: () => {
            bulkSaving.value = false
        }
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

                <button
                    class="px-3 py-1.5 rounded-lg bg-white border text-sm flex items-center gap-2 disabled:opacity-60"
                    :disabled="bulkSaving"
                    @click="resetPriorities(3)"
                    title="Reset priorities to 3 (respects status filter if set)"
                >
                    <svg v-if="bulkSaving" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                    <span>Reset priorities</span>
                </button>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow divide-y">
            <div v-for="a in animals" :key="a.id" class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-medium">
                        {{ a.name }} <span class="text-gray-400">— {{ a.species }}</span>
                    </div>
                    <div class="text-xs text-gray-600">
                        Status: <span class="capitalize">{{ a.status }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-sm">
                        Priority:
                        <span class="font-semibold">{{ a.priority }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-sm flex items-center gap-2 disabled:opacity-60"
                            :disabled="saving[a.id]"
                            @click="bump(a, -1)"
                        >
                            <svg v-if="saving[a.id]" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <span>Bump ↑</span>
                        </button>

                        <button
                            class="px-3 py-1.5 rounded-lg bg-white border text-sm disabled:opacity-60"
                            :disabled="saving[a.id]"
                            @click="bump(a, +1)"
                        >
                            Lower ↓
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <Toast v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </div>
</template>
