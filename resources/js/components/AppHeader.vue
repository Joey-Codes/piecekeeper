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

                <!-- Navigation tabs + profile -->
                <div class="flex items-center gap-1">
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

                    <!-- Profile dropdown -->
                    <div class="relative ml-2">
                        <button
                            class="w-8 h-8 rounded-full bg-linear-to-br from-amber-500 to-orange-400 flex items-center justify-center text-sm font-semibold text-white hover:from-amber-600 hover:to-orange-500 transition-all duration-150 shadow-sm"
                            @click="profileOpen = !profileOpen"
                        >
                            {{ userInitial }}
                        </button>
                        <Transition name="dropdown">
                            <div
                                v-if="profileOpen"
                                class="absolute right-0 mt-2 w-44 bg-white rounded-xl border border-stone-200 shadow-lg py-1.5 z-50"
                            >
                                <button
                                    class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors"
                                    @click="logout"
                                >
                                    Log out
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
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
                <button
                    class="block w-full text-left px-6 py-3 text-sm font-semibold uppercase tracking-wider text-red-600 hover:bg-red-50 transition-all duration-200"
                    @click="logout"
                >
                    Log out
                </button>
            </nav>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api'
import { auth } from '@/auth'

const route = useRoute()
const router = useRouter()
const menuOpen = ref(false)
const profileOpen = ref(false)

const userInitial = computed(() => {
    const name = auth.user?.name || ''
    return name.charAt(0).toUpperCase() || '?'
})

function onClickOutside(e) {
    if (profileOpen.value && !e.target.closest('.relative')) {
        profileOpen.value = false
    }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))

async function logout() {
    profileOpen.value = false
    menuOpen.value = false
    try {
        await api.post('/api/logout')
        auth.clear()
        router.push({ name: 'login' })
    } catch { /* ignore */ }
}

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

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
