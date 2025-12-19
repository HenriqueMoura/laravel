<script setup lang="ts">
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <a href="javascript:void(0);" class="dropdown-item notify-item">
        <i class="fe-user me-1"></i>
        <span>My Account</span>
    </a>

    <Link :href="edit()" class="dropdown-item notify-item">
        <i class="fe-settings me-1"></i>
        <span>Settings</span>
    </Link>

    <a href="javascript:void(0);" class="dropdown-item notify-item">
        <i class="fe-lock me-1"></i>
        <span>Lock Screen</span>
    </a>

    <div class="dropdown-divider"></div>

    <Link
        :href="logout()"
        @click="handleLogout"
        class="dropdown-item notify-item"
        as="button"
    >
        <i class="fe-log-out me-1"></i>
        <span>Logout</span>
    </Link>
</template>

