<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-6 sm:mb-10 text-center">
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    <span class="bg-linear-to-r bg-clip-text">Settings</span>
                </h1>
                <p class="text-sm sm:text-base font-semibold text-stone-500 mt-1.5 sm:mt-2">
                    Customize your practice experience and manage your account
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
                            <span class="flex items-center gap-2">
                                <span
                                    class="w-4 h-4 shrink-0"
                                    v-html="tab.icon"
                                />
                                <span class="text-sm sm:text-base font-semibold">{{ tab.label }}</span>
                            </span>
                        </button>
                    </div>
                </nav>

                <!-- Content Panel -->
                <div class="flex-1 min-w-0 p-4 sm:p-8 min-h-140 sm:min-h-160">
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
    {
        id: 'schedule',
        label: 'Practice Schedule',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
    },
    {
        id: 'preferences',
        label: 'Preferences',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>',
    },
    {
        id: 'account',
        label: 'Account',
        icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>',
    },
]
</script>
