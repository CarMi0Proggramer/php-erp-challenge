<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { users } from '@/routes/dashboard';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Usuários',
                href: users(),
            },
        ],
    },
});
</script>
<template>
    <Head title="Users" />
</template>
