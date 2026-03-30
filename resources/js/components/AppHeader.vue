<template>
    <header class="fixed top-0 left-0 right-0 z-50">
        <!-- Desktop piano header -->
        <div class="hidden lg:block h-[68px] border-b border-stone-200 shadow-sm bg-linear-to-b from-stone-50 to-white">
            <!-- Decorative keyboard background (edge to edge) -->
            <div class="absolute inset-0 flex justify-center pointer-events-none overflow-hidden">
                <div
                    v-for="i in 53"
                    :key="'key-' + i"
                    class="shrink-0"
                >
                    <div
                        v-if="isBlackKey(i)"
                        class="w-3 h-7 bg-stone-900/[0.07] -mx-1.5 relative z-1 rounded-b-sm"
                    />
                    <div
                        v-else
                        class="w-6 h-full border-r border-stone-200/30"
                    />
                </div>
            </div>

            <!-- Header content (logo + tabs) -->
            <div class="relative z-10 max-w-5xl mx-auto px-6 flex items-center justify-between h-full">
                <!-- Logo -->
                <router-link
                    to="/dashboard"
                    class="flex items-center gap-2.5 no-underline"
                >
                    <div class="w-8 h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm">&#9835;</span>
                    </div>
                    <span class="text-lg font-serif font-bold tracking-wide text-stone-800">
                        Piece<span class="text-amber-500">Keeper</span>
                    </span>
                </router-link>

                <!-- Navigation tabs -->
                <nav class="flex items-center gap-1">
                    <router-link
                        v-for="tab in tabs"
                        :key="tab.to"
                        :to="tab.to"
                        class="relative px-4 py-1.5 text-sm font-semibold uppercase tracking-wider rounded-lg transition-all duration-200 no-underline"
                        :class="isActive(tab.to)
                            ? 'text-white bg-amber-500 shadow-sm'
                            : 'text-stone-600 hover:bg-white/80 hover:text-stone-800'"
                    >
                        {{ tab.label }}
                    </router-link>
                </nav>
            </div>
        </div>

        <!-- Mobile header -->
        <div class="lg:hidden relative bg-linear-to-b from-stone-50 to-white backdrop-blur-lg border-b border-stone-200 shadow-sm overflow-hidden">
            <!-- Decorative keyboard background -->
            <div class="absolute inset-0 flex justify-center pointer-events-none">
                <div
                    v-for="i in 29"
                    :key="'mkey-' + i"
                    class="shrink-0"
                >
                    <div
                        v-if="isBlackKey(i)"
                        class="w-2.5 h-5 bg-stone-900/[0.07] -mx-1 relative z-1 rounded-b-sm"
                    />
                    <div
                        v-else
                        class="w-5 h-full border-r border-stone-200/30"
                    />
                </div>
            </div>

            <div class="relative z-10 px-4 sm:px-6 flex items-center justify-between h-14 sm:h-16">
                <!-- Logo -->
                <router-link
                    to="/dashboard"
                    class="flex items-center gap-2 no-underline"
                >
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center shadow-sm">
                        <span class="text-white text-xs sm:text-sm">&#9835;</span>
                    </div>
                    <span class="text-base sm:text-lg font-serif font-bold tracking-wide text-stone-800">
                        Piece<span class="text-amber-500">Keeper</span>
                    </span>
                </router-link>

                <!-- Hamburger button (mobile) -->
                <button
                    class="flex flex-col justify-center items-center w-8 h-8 gap-1.5"
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
            </div>

            <!-- Mobile dropdown menu -->
            <nav
                v-if="menuOpen"
                class="border-t border-stone-200 bg-white/95 backdrop-blur-lg"
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
        </div>
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

// Real piano pattern: 12 notes per octave — W B W B W W B W B W B W
// Black keys at positions 1,3 (group of 2) and 6,8,10 (group of 3)
function isBlackKey(i) {
    const mod = (i - 1) % 12
    return mod === 1 || mod === 3 || mod === 6 || mod === 8 || mod === 10
}

function isActive(path) {
    return route.path === path
}
</script>
