<template>
    <footer class="bg-stone-900 text-stone-400 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center">
                        <span class="text-white text-sm">&#9835;</span>
                    </div>
                    <span class="font-serif text-lg font-semibold text-white">
                        Piano <span class="text-amber-400">Piece Manager</span>
                    </span>
                </div>
                <div class="text-sm">
                    &copy; {{ currentYear }} Piano Piece Manager. Made with &#9829; for pianists.
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear()
</script>
