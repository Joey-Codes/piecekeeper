<template>
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-lg border-b border-stone-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-6 flex items-center justify-between h-14">
            <!-- Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
                    <span class="text-white text-sm">&#9835;</span>
                </div>
                <span class="text-lg font-serif font-bold tracking-wide text-stone-800">
                    Piano <span class="text-amber-500">Piece Manager</span>
                </span>
            </div>

            <!-- Navigation -->
            <nav class="flex items-center gap-1">
                <router-link
                    v-for="tab in tabs"
                    :key="tab.to"
                    :to="tab.to"
                    class="relative px-4 py-1.5 text-sm font-semibold uppercase tracking-wider rounded-lg transition-all duration-200"
                    :class="isActive(tab.to)
                        ? 'text-white bg-amber-500 shadow-sm'
                        : 'text-stone-700 hover:bg-stone-100'"
                >
                    {{ tab.label }}
                </router-link>
            </nav>
        </div>
    </header>
</template>

<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const tabs = [
    { to: '/dashboard', label: 'Practice' },
    { to: '/repertoire', label: 'My Repertoire' },
    { to: '/insights', label: 'Insights' },
    { to: '/settings', label: 'Settings' },
]

function isActive(path) {
    return route.path === path
}
</script>
