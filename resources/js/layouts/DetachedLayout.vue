<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';
import DetachedMenu from '@/components/DetachedMenu.vue';
import DetachedTopbar from '@/components/DetachedTopbar.vue';

defineProps<{
  title?: string;
}>();

onMounted(() => {
  if (typeof document !== 'undefined') {
    document.documentElement.setAttribute('data-layout-mode', 'detached');
    document.body.classList.remove('authentication-bg', 'authentication-bg-pattern');
    if (!document.body.classList.contains('font-sans')) {
      document.body.classList.add('font-sans', 'antialiased');
    }
  }
});

onBeforeUnmount(() => {
  if (typeof document !== 'undefined') {
    document.documentElement.removeAttribute('data-layout-mode');
  }
});

</script>

<template>

  <Head :title="title" />
  <div id="wrapper">
    <DetachedMenu />

    <div class="content-page">
      <DetachedTopbar />

      <div class="content">
        <div class="container-fluid">
          <slot />
        </div>
      </div>

      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div>
                <script>document.write(new Date().getFullYear())</script> © Ubold -
                <a href="https://coderthemes.com/" target="_blank">Coderthemes.com</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                <a href="javascript: void(0);">About</a>
                <a href="javascript: void(0);">Support</a>
                <a href="javascript: void(0);">Contact Us</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>
