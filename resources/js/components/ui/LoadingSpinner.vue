<template>
    <div class="flex flex-col items-center justify-center py-16 gap-4">
        <div class="relative w-10 h-10">
            <div class="absolute inset-0 rounded-full border-[3px] border-stone-200" />
            <div class="absolute inset-0 rounded-full border-[3px] border-transparent border-t-amber-500 animate-spin" />
        </div>
        <p v-if="message" class="text-sm font-semibold text-stone-400">
            {{ message }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    message: { type: String, default: '' },
})
</script>
