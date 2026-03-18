import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from './components/dashboard/Dashboard.vue'
import Repertoire from './components/repertoire/Repertoire.vue'
import Insights from './components/insights/Insights.vue'
import Settings from './components/settings/Settings.vue'
import SignUp from './components/auth/SignUp.vue'
import Login from './components/auth/Login.vue'
import ForgotPassword from './components/auth/ForgotPassword.vue'
import TermsOfService from './components/legal/TermsOfService.vue'
import PrivacyPolicy from './components/legal/PrivacyPolicy.vue'

const routes = [
    { path: '/signup', name: 'signup', component: SignUp },
    { path: '/login', name: 'login', component: Login },
    { path: '/forgot-password', name: 'forgot-password', component: ForgotPassword },
    { path: '/terms', name: 'terms', component: TermsOfService },
    { path: '/privacy', name: 'privacy', component: PrivacyPolicy },
    { path: '/dashboard', name: 'dashboard', component: Dashboard },
    { path: '/repertoire', name: 'repertoire', component: Repertoire },
    { path: '/insights', name: 'insights', component: Insights },
    { path: '/settings', name: 'settings', component: Settings },
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

export default router
