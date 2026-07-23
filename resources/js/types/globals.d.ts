import { AppPageProps } from '@/js/types/index';
import type { Auth } from '@/js/types/auth';

declare global {
    interface TurnstileRenderOptions {
        sitekey: string;
        callback: (token: string) => void;
        [key: string]: unknown;
    }

    interface TurnstileInstance {
        render(container: string | HTMLElement, options: TurnstileRenderOptions): string;
        remove(widgetId: string): void;
        reset(widgetId?: string): void;
    }

    const turnstile: TurnstileInstance;

    interface Window {
        turnstile: TurnstileInstance;
    }
}

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        readonly VITE_TURNSTILE_SITE_KEY: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}

    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}

declare module '@/js/data/projects.json' {
    import { Project } from '@/js/types/index';
    const projects: Project[];
    export default projects;
}
