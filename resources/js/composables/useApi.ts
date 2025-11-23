import { ref, type Ref } from 'vue';
import * as Sentry from '@sentry/vue';
import { toast } from 'vue3-toastify';

export interface ApiResponse<T> {
    success: boolean;
    data?: T;
    message?: string;
    errors?: Record<string, any>;
}

export interface UseApiFetchOptions {
    successMessage?: string;
    errorMessage?: string;
    showSuccessToast?: boolean;
    showErrorToast?: boolean;
    skipToastOnError?: boolean;
    skipSentryOnError?: boolean;
}

export function useApiFetch<T>(
    url: string,
    options: UseApiFetchOptions = {}
): {
    data: Ref<T | null>;
    loading: Ref<boolean>;
    error: Ref<string | null>;
    execute: () => Promise<void>;
} {
    const data = ref<T | null>(null) as Ref<T | null>;
    const loading = ref(false);
    const error = ref<string | null>(null);

    const {
        successMessage,
        errorMessage,
        showSuccessToast = false, // Default to false - only show for user actions
        showErrorToast = true,
        skipToastOnError = false,
        skipSentryOnError = false,
    } = options;

    const execute = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                const message = errorData.message || errorMessage || `Failed to fetch data from ${url}`;
                const error = new Error(message);
                // Attach status code for filtering
                (error as any).status = response.status;
                throw error;
            }

            const result: ApiResponse<T> = await response.json();

            if (result.success && result.data !== undefined) {
                data.value = result.data;

                // Show success toast if enabled and message provided
                if (showSuccessToast && successMessage) {
                    toast.success(successMessage, {
                        autoClose: 3000,
                    });
                }
            } else {
                const message = result.message || errorMessage || 'Invalid response format';
                throw new Error(message);
            }
        } catch (err) {
            const errorMsg = err instanceof Error ? err.message : 'An unexpected error occurred';
            error.value = errorMsg;

            const statusCode = (err as any)?.status;

            // Send to Sentry with context (unless skipped or 404 for optional data)
            if (!skipSentryOnError && statusCode !== 404) {
                Sentry.captureException(err, {
                    tags: {
                        api_endpoint: url,
                        status_code: statusCode,
                    },
                    extra: {
                        url,
                        errorMessage: errorMsg,
                        statusCode,
                    },
                });
            }

            // Show error toast if enabled and not skipped
            if (showErrorToast && !skipToastOnError) {
                toast.error(errorMsg, {
                    autoClose: 5000,
                });
            }
        } finally {
            loading.value = false;
        }
    };

    return {
        data,
        loading,
        error,
        execute,
    };
}

