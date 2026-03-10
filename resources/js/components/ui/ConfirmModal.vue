<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4" @mousedown.self="cancel">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" />

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 space-y-4">
          <!-- Icon -->
          <div v-if="$slots.icon" class="flex justify-center">
            <slot name="icon" />
          </div>

          <!-- Title -->
          <h3 class="text-xl font-serif font-bold uppercase tracking-wide text-stone-800 text-center">{{ title }}</h3>

          <!-- Message -->
          <p v-if="message" class="text-md text-stone-600 text-center leading-relaxed">{{ message }}</p>

          <!-- Actions -->
          <div class="flex gap-3 pt-2">
            <button
              @click="cancel"
              class="flex-1 px-4 py-2.5 text-sm font-semibold text-stone-600 bg-stone-100 rounded-xl hover:bg-stone-200 transition-colors duration-150"
            >
              {{ cancelLabel }}
            </button>
            <button
              @click="confirm"
              class="flex-1 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all duration-150 hover:scale-[1.02]"
              :class="confirmClass"
            >
              {{ confirmLabel }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  show: { type: Boolean, default: false },
  title: { type: String, default: 'Are you sure?' },
  message: { type: String, default: '' },
  confirmLabel: { type: String, default: 'Confirm' },
  cancelLabel: { type: String, default: 'Cancel' },
  confirmClass: { type: String, default: 'bg-linear-to-r from-rose-500 to-pink-500 hover:shadow-md' },
})

const emit = defineEmits(['confirm', 'cancel'])

function confirm() {
  emit('confirm')
}

function cancel() {
  emit('cancel')
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}
.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-from .relative {
  transform: scale(0.95);
}
.modal-leave-to .relative {
  transform: scale(0.95);
}
</style>
