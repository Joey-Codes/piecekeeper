<template>
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-lg border-b border-stone-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center justify-between h-12 sm:h-14">
            <!-- Logo -->
            <router-link
                to="/dashboard"
                class="flex items-center gap-2 no-underline"
            >
                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs sm:text-sm">&#9835;</span>
                </div>
                <span class="text-base sm:text-lg font-serif font-bold tracking-wide text-stone-800">
                    Piano <span class="text-amber-500">Piece Manager</span>
                </span>
            </router-link>

            <!-- Hamburger button (mobile) -->
            <button
                class="lg:hidden flex flex-col justify-center items-center w-8 h-8 gap-1.5"
                aria-label="Toggle menu"
                @click="menuOpen = !menuOpen"
            >
                <span
                    class="block w-5 h-0.5 bg-stone-700 rounded transition-all duration-200"
                    :class="menuOpen ? 'rotate-45 translate-y-1' : ''"
                />
                <span
                    class="block w-5 h-0.5 bg-stone-700 rounded transition-all duration-200"
                    :class="menuOpen ? 'opacity-0' : ''"
                />
                <span
                    class="block w-5 h-0.5 bg-stone-700 rounded transition-all duration-200"
                    :class="menuOpen ? '-rotate-45 -translate-y-1' : ''"
                />
            </button>

            <!-- Desktop navigation -->
            <nav class="hidden lg:flex items-center gap-1">
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

        <!-- Mobile dropdown menu -->
        <nav
            v-if="menuOpen"
            class="lg:hidden border-t border-stone-200 bg-white/95 backdrop-blur-lg"
        >
            <router-link
                v-for="tab in tabs"
                :key="tab.to"
                :to="tab.to"
                class="block px-6 py-3 text-sm font-semibold uppercase tracking-wider transition-all duration-200"
                :class="isActive(tab.to)
                    ? 'text-white bg-amber-500'
                    : 'text-stone-700 hover:bg-stone-100'"
                @click="menuOpen = false"
            >
                {{ tab.label }}
            </router-link>
        </nav>
    </header>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const menuOpen = ref(false)

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
