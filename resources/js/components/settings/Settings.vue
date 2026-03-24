<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-6 sm:mb-10 text-center">
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    <span class="bg-linear-to-r bg-clip-text">Settings</span>
                </h1>
                <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                    Customize your practice experience
                </p>
                <div class="mt-2.5 sm:mt-3 mx-auto w-12 sm:w-16 h-1 rounded-full bg-linear-to-r from-amber-400 to-orange-400 opacity-60" />
            </header>

            <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 rounded-2xl bg-white border border-stone-200/70 shadow-sm overflow-hidden">
                <!-- Sidebar Navigation -->
                <nav class="sm:w-60 shrink-0 sm:border-r sm:border-amber-600/30 bg-amber-500 px-2 py-3 sm:py-5">
                    <div class="flex sm:flex-col gap-1 sm:gap-0.5 overflow-x-auto sm:overflow-x-visible pb-2 sm:pb-0">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="px-3 sm:px-5 py-2.5 sm:py-3.5 rounded-xl text-left transition-all duration-200 whitespace-nowrap shrink-0 sm:shrink sm:w-full"
                            :class="activeTab === tab.id
                                ? 'bg-white/90 text-stone-800 shadow-sm'
                                : 'text-white hover:bg-white/20 hover:text-white'"
                            @click="activeTab = tab.id"
                        >
                            <span class="text-sm sm:text-base font-semibold">{{ tab.label }}</span>
                        </button>
                    </div>
                </nav>

                <!-- Content Panel -->
                <div class="flex-1 min-w-0 p-4 sm:p-8">
                    <PracticeSchedule v-if="activeTab === 'schedule'" />
                    <Preferences v-else-if="activeTab === 'preferences'" />
                    <AccountSettings v-else-if="activeTab === 'account'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import FloatingNotes from '../ui/FloatingNotes.vue'
import PracticeSchedule from './PracticeSchedule.vue'
import Preferences from './Preferences.vue'
import AccountSettings from './AccountSettings.vue'

const activeTab = ref('schedule')

const tabs = [
    { id: 'schedule', label: 'Practice Schedule' },
    { id: 'preferences', label: 'Preferences' },
    { id: 'account', label: 'Account' },
]
</script>
