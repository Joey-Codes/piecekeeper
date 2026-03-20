<template>
    <div class="flex flex-col min-h-screen">
        <!-- Full-page routes (landing, signup, login) -->
        <router-view v-if="isFullPageRoute" />

        <!-- Main app layout -->
        <template v-else>
            <FloatingNotes />
            <AppHeader />
            <div class="pt-14 flex-1">
                <router-view />
            </div>
            <AppFooter />
        </template>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
import FloatingNotes from './components/ui/FloatingNotes.vue'

const route = useRoute()

const fullPageRoutes = ['/', '/signup', '/login', '/forgot-password', '/terms', '/privacy']
const isFullPageRoute = computed(() => fullPageRoutes.includes(route.path))
</script>
