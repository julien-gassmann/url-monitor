const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL;

if (!API_BASE_URL) {
    throw new Error('NEXT_PUBLIC_API_URL is not defined');
}

export type ApiResponse<R, E> = {
    ok: boolean;
    status: number;
    data?: R;
    errors?: E;
    message?: string;
};

async function apiFetch<R, E>(endpoint: string, options?: RequestInit): Promise<ApiResponse<R, E>> {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            ...options?.headers,
        },
        ...options,
    });

    const json = response.ok ? await response.json() : undefined;

    return {
        ok: response.ok,
        status: response.status,
        data: json,
        errors: json?.errors,
        message: json?.message,
    };
}

export async function apiPost<P, R, E>(endpoint: string, body: P): Promise<ApiResponse<R, E>> {
    return apiFetch<R, E>(endpoint, {
        method: 'POST',
        body: JSON.stringify(body),
    });
}

export async function apiGet<R, E>(endpoint: string): Promise<ApiResponse<R, E>> {
    return apiFetch<R, E>(endpoint);
}
