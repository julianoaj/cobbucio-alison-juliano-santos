import { AppPageProps } from '@/types/index';
import { AxiosInstance } from 'axios';
import type { route as ziggyRoute, Ziggy } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
        Echo: Echo;
        Pusher: Pusher;
        Ziggy?: Ziggy;
    }

    const route: typeof ziggyRoute;
}

declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
