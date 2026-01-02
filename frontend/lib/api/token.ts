

import {apiGet} from './client';
import {VerifyTokenResponse} from "@/types/token.type";

const baseUrl = '/tokens';

export async function verifyAccessToken(token?: string) {
    const url = `${baseUrl}/verify/${token}`;
    return apiGet<VerifyTokenResponse, any>(url);
}

export async function refreshAccessToken(token?: string) {
    const url = `${baseUrl}/refresh/${token}`;
    return apiGet<any, any>(url);
}
