<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50/80 via-white to-stone-50 relative overflow-hidden">
        <FloatingNotes />

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <header class="mb-6 sm:mb-10 flex flex-col items-center">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-md mb-4 sm:mb-5">
                    <svg
                        class="w-5 h-5 sm:w-7 sm:h-7 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-4xl font-serif font-bold uppercase tracking-wide text-stone-800">
                    <span class="bg-linear-to-r text-black bg-clip-text">Settings</span>
                </h1>
                <p class="text-sm sm:text-base font-medium text-stone-700 mt-1.5 sm:mt-2">
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
