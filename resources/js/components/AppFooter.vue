<template>
    <footer class="bg-white/90 backdrop-blur-lg border-t border-stone-200 shadow-[0_-1px_3px_rgba(0,0,0,0.05)]">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-stone-500">
                <div class="w-5 h-5 rounded bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center">
                    <span class="text-white text-[10px]">&#9835;</span>
                </div>
                <span class="font-serif font-semibold tracking-wide text-stone-600">
                    Piano <span class="text-amber-700">Piece Manager</span>
                </span>
            </div>
            <div class="text-xs text-stone-400">
                &copy; {{ currentYear }}
            </div>
        </div>
    </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear()
</script>
