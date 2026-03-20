import { createRouter, createWebHistory } from 'vue-router'
import { auth } from '@/auth'
import Dashboard from './components/dashboard/Dashboard.vue'
import Repertoire from './components/repertoire/Repertoire.vue'
import Insights from './components/insights/Insights.vue'
import Settings from './components/settings/Settings.vue'
import SignUp from './components/auth/SignUp.vue'
import Login from './components/auth/Login.vue'
import ForgotPassword from './components/auth/ForgotPassword.vue'
import TermsOfService from './components/legal/TermsOfService.vue'
import PrivacyPolicy from './components/legal/PrivacyPolicy.vue'
import LandingPage from './components/landing/LandingPage.vue'

const routes = [
    { path: '/', name: 'landing', component: LandingPage, meta: { guest: true } },
    { path: '/signup', name: 'signup', component: SignUp, meta: { guest: true } },
    { path: '/login', name: 'login', component: Login, meta: { guest: true } },
    { path: '/forgot-password', name: 'forgot-password', component: ForgotPassword },
    { path: '/terms', name: 'terms', component: TermsOfService },
    { path: '/privacy', name: 'privacy', component: PrivacyPolicy },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/repertoire', name: 'repertoire', component: Repertoire, meta: { requiresAuth: true } },
    { path: '/insights', name: 'insights', component: Insights, meta: { requiresAuth: true } },
    { path: '/settings', name: 'settings', component: Settings, meta: { requiresAuth: true } },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to) {
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' }
        }
        return { top: 0 }
    },
})

router.beforeEach(async (to) => {
    if (!auth.checked) {
        await auth.fetchUser()
    }

    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        return { name: 'login' }
    }

    if (to.meta.guest && auth.isLoggedIn) {
        return { name: 'dashboard' }
    }
})

export default router
