<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    message: { type: String, default: '' },
    type: { type: String, default: 'success' }, // success | error
    duration: { type: Number, default: 2500 }   // ms
})

const visible = ref(false)
let timer

onMounted(() => {
    if (props.message) {
        visible.value = true
        timer = setTimeout(() => (visible.value = false), props.duration)
    }
})

onBeforeUnmount(() => clearTimeout(timer))
</script>
<template>
    <transition name="fade">
        <div v-if="visible"
             class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded-lg shadow-lg text-sm"
             :class="type === 'success'
           ? 'bg-emerald-600 text-white'
           : 'bg-rose-600 text-white'">
            {{ message }}
        </div>
    </transition>
</template>
<style>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
