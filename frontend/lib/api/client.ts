import { emitPendingApiCall } from '@/lib/events/PendingApiCallEvent';
import type { ApiResponse } from '@/types/api.type';

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL;

if (!API_BASE_URL) {
    throw new Error('NEXT_PUBLIC_API_URL is not defined');
}

async function apiFetch<R, E>(
    endpoint: string,
    callId: string,
    options?: RequestInit
): Promise<ApiResponse<R, E>> {
    emitPendingApiCall(callId, true);

    try {
        const token = localStorage.getItem('bearer_token');
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
                ...options?.headers,
            },
            ...options,
        });

        const json = await response.json();

        return {
            ok: response.ok,
            status: response.status,
            data: json?.data,
            errors: json?.errors,
            message: json?.message,
            links: json?.links,
            meta: json?.meta,
        };
    } finally {
        emitPendingApiCall(callId, false);
    }
}

export async function apiPost<P, R, E>(
    endpoint: string,
    callId: string,
    body: P
): Promise<ApiResponse<R, E>> {
    return apiFetch<R, E>(endpoint, callId, {
        method: 'POST',
        body: JSON.stringify(body),
    });
}

export async function apiGet<R, E>(endpoint: string, callId: string): Promise<ApiResponse<R, E>> {
    return apiFetch<R, E>(endpoint, callId);
}
