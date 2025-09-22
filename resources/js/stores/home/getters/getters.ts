import { computed } from 'vue';
import { page } from '@/stores/home/states';
import { usePage } from '@inertiajs/vue3';

export const hasActivePage = computed(() => {
    return !!page.value;
})

export const getActualUrl = computed<string>(() => {
    return usePage().url
})
