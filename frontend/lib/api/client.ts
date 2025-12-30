const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL;

type ApiError<E> = {
    message?: string
    errors?: E
}

type ApiResponse<R, E> = R | ApiError<E>

async function apiFetch<R, E>(
    endpoint: string,
    options?: RequestInit
): Promise<ApiResponse<R, E>> {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options?.headers,
        },
        ...options,
    });

    return response.json();
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

export function isApiError<E>(response: unknown): response is ApiError<E> {
    return response.hasOwnProperty('errors')
}