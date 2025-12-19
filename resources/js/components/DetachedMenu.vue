<script setup lang="ts">
import DetachedUserMenu from '@/components/DetachedUserMenu.vue';
import { getInitials } from '@/composables/useInitials';
import { urlIsActive } from '@/lib/utils';
import { dashboard } from '@/routes';
import ticketsRoutes from '@/routes/tickets';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const isTicketsRoute = computed(() => {
  const currentUrl = page.url;
  return currentUrl.startsWith('/tickets');
});

const isTicketsListActive = computed(() => {
  return urlIsActive(ticketsRoutes.index(), page.url);
});

const isTicketsDetailActive = computed(() => {
  return page.url.startsWith('/tickets/') && !page.url.includes('/edit') && !page.url.includes('/create');
});

</script>

<template>
  <div class="app-menu" :class="{ 'menuitem-active': isTicketsRoute }">

    <div class="logo-box">
      <Link :href="dashboard()" class="logo-light">
        <img src="/assets/images/logo-light.png" alt="logo" class="logo-lg">
        <img src="/assets/images/logo-sm.png" alt="small logo" class="logo-sm">
      </Link>

      <!-- Brand Logo Dark -->
      <Link :href="dashboard()" class="logo-dark">
        <img src="/assets/images/logo-dark.png" alt="dark logo" class="logo-lg">
        <img src="/assets/images/logo-sm.png" alt="small logo" class="logo-sm">
      </Link>
    </div>

    <!-- menu-left -->
    <div class="scrollbar show h-100" data-simplebar="init">
      <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
          <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
          <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
            <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
              style="height: 100%; overflow: hidden scroll;">
              <div class="simplebar-content" style="padding: 0px;">

                <!-- User box -->
                <div class="user-box text-center">
                  <img v-if="user.avatar" :src="user.avatar" :alt="user.name" class="rounded-circle avatar-md">
                  <div v-else
                    class="rounded-circle avatar-md bg-primary d-flex align-items-center justify-content-center text-white fw-bold">
                    {{ getInitials(user.name) }}
                  </div>
                  <div class="dropdown">
                    <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">{{
                      user.name }}</a>
                    <div class="dropdown-menu user-pro-dropdown">
                      <DetachedUserMenu :user="user" />
                    </div>
                  </div>
                  <p class="text-muted mb-0">Admin Head</p>
                </div>

                <!--- Menu -->
                <ul class="menu">

                  <li class="menu-title">Navigation</li>

                  <li class="menu-item">
                    <Link :href="dashboard()" class="menu-link">
                      <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-airplay">
                          <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                          <polygon points="12 15 17 21 7 21 12 15"></polygon>
                        </svg></span>
                      <span class="menu-text"> Dashboard </span>
                    </Link>
                  </li>

                  <li class="menu-item">
                    <Link :href="ticketsRoutes.index()" class="menu-link">
                      <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-aperture">
                          <circle cx="12" cy="12" r="10"></circle>
                          <line x1="14.31" y1="8" x2="20.05" y2="17.94"></line>
                          <line x1="9.69" y1="8" x2="21.17" y2="8"></line>
                          <line x1="7.38" y1="12" x2="13.12" y2="2.06"></line>
                          <line x1="9.69" y1="16" x2="3.95" y2="6.06"></line>
                          <line x1="14.31" y1="16" x2="2.83" y2="16"></line>
                          <line x1="16.62" y1="12" x2="10.88" y2="21.94"></line>
                        </svg></span>
                      <span class="menu-text"> Tickets </span>
                    </Link>
                  </li>
                </ul>
                <!--- End Menu -->
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
