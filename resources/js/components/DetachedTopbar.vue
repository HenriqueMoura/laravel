<script setup lang="ts">
import DetachedUserMenu from '@/components/DetachedUserMenu.vue';
import { getInitials } from '@/composables/useInitials';
import { dashboard } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

</script>

<template>
    <div class="navbar-custom">
        <div class="topbar">
            <div class="topbar-menu d-flex align-items-center gap-1">
                <div class="logo-box">
                    <Link :href="dashboard()" class="logo-light">
                        <img src="/assets/images/logo-light.png" alt="logo" class="logo-lg">
                        <img src="/assets/images/logo-sm.png" alt="small logo" class="logo-sm">
                    </Link>

                    <Link :href="dashboard()" class="logo-dark">
                        <img src="/assets/images/logo-dark.png" alt="dark logo" class="logo-lg">
                        <img src="/assets/images/logo-sm.png" alt="small logo" class="logo-sm">
                    </Link>
                </div>

                <button class="button-toggle-menu">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>

            <ul class="topbar-menu d-flex align-items-center">
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img 
                            v-if="user.avatar" 
                            :src="user.avatar" 
                            :alt="user.name" 
                            class="rounded-circle"
                        >
                        <div v-else class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 36px; height: 36px;">
                            {{ getInitials(user.name) }}
                        </div>
                        <span class="ms-1 d-none d-md-inline-block">
                            {{ user.name }} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>
                        <DetachedUserMenu :user="user" />
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

