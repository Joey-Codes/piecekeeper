<template>
  <div>
    <FloatingNotes />
    <AppHeader :activeTab="activeTab" @navigate="activeTab = $event" />
    <div class="pt-14">
      <Dashboard v-if="activeTab === 'practice'" />
      <Repertoire v-else-if="activeTab === 'repertoire'" />
      <Insights v-else-if="activeTab === 'insights'" />
      <Settings v-else-if="activeTab === 'settings'" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppHeader from './components/AppHeader.vue'
import FloatingNotes from './components/ui/FloatingNotes.vue'
import Dashboard from './components/dashboard/Dashboard.vue'
import Repertoire from './components/repertoire/Repertoire.vue'
import Insights from './components/insights/Insights.vue'
import Settings from './components/settings/Settings.vue'

const activeTab = ref('practice')
</script>
