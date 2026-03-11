<template>
    <div class="flex flex-col min-h-screen">
        <!-- Landing page (not logged in) -->
        <LandingPage v-if="!isLoggedIn" @get-started="isLoggedIn = true" />

        <!-- Main app (logged in) -->
        <template v-else>
            <FloatingNotes />
            <AppHeader
                :active-tab="activeTab"
                @navigate="activeTab = $event"
            />
            <div class="pt-14 flex-1">
                <Dashboard v-if="activeTab === 'practice'" />
                <Repertoire v-else-if="activeTab === 'repertoire'" />
                <Insights v-else-if="activeTab === 'insights'" />
                <Settings v-else-if="activeTab === 'settings'" />
            </div>
            <AppFooter />
        </template>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import LandingPage from './components/landing/LandingPage.vue'
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
import FloatingNotes from './components/ui/FloatingNotes.vue'
import Dashboard from './components/dashboard/Dashboard.vue'
import Repertoire from './components/repertoire/Repertoire.vue'
import Insights from './components/insights/Insights.vue'
import Settings from './components/settings/Settings.vue'

const isLoggedIn = ref(false)
const activeTab = ref('practice')
</script>
