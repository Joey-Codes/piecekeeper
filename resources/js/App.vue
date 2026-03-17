<template>
    <div class="flex flex-col min-h-screen">
        <!-- Landing page at / -->
        <LandingPage v-if="isLandingPage" @get-started="goToSignUp" />

        <!-- Full-page routes (signup, login) -->
        <router-view v-else-if="isFullPageRoute" />

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
import { useRoute, useRouter } from 'vue-router'
import LandingPage from './components/landing/LandingPage.vue'
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
import FloatingNotes from './components/ui/FloatingNotes.vue'

const route = useRoute()
const router = useRouter()

const isLandingPage = computed(() => route.path === '/')
const fullPageRoutes = ['/signup', '/login', '/forgot-password', '/terms', '/privacy']
const isFullPageRoute = computed(() => fullPageRoutes.includes(route.path))

function goToSignUp() {
    router.push('/signup')
}
</script>
