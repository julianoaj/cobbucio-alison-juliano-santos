import { computed } from 'vue';
import { page } from '@/stores/home/states';
import { usePage } from '@inertiajs/vue3';

export const hasActivePage = computed(() => {
    return !!page.value;
})

export const getActivePage = computed<string>(() => {
    const urlPage = usePage().url

    if (urlPage.includes('profile')) return 'profile';
    if (urlPage.includes('settings')) return 'settings';
    if (urlPage.includes('wallet')) return 'wallet';

    return 'dashboard';
})
