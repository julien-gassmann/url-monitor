import type { VerifyTokenError, VerifyTokenResponse } from '@/types/token.type';

import { apiGet } from './client';

const baseUrl = '/tokens';

export async function verifyAccessToken(token?: string) {
    const url = `${baseUrl}/verify/${token}`;
    const callId = 'verify-token';
    return apiGet<VerifyTokenResponse, VerifyTokenError>(url, callId);
}

export async function refreshAccessToken(token?: string) {
    const url = `${baseUrl}/refresh/${token}`;
    const callId = 'refresh-token';
    return apiGet<[], []>(url, callId);
}
