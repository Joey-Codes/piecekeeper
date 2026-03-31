<template>
    <div class="flex flex-col items-center justify-center py-16 gap-4">
        <div class="w-10 h-10 rounded-full border-[3px] border-amber-400 border-t-transparent animate-spin" />
        <p
            v-if="message"
            class="text-sm font-semibold text-stone-700"
        >
            {{ message }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    message: { type: String, default: '' },
})
</script>
