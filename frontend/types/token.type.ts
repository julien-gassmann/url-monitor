export type VerifyTokenResponse = {
    is_valid: boolean;
    reason: string | null;
    monitor_uuid: string | null;
    access_token: string | null;
    token_type: string | null;
    expires_at: string | null;
};

export type VerifyTokenError = VerifyTokenResponse;

export type VerifyTokenParams = {
    token?: string;
};
