type BearerToken = {
    token: string | null;
    expires_at: string | null;
};

export type VerifyTokenResult = {
    is_valid: boolean;
    reason: string | null;
    monitor_uuid: string | null;
    bearer: BearerToken;
};

export type VerifyTokenResponse = VerifyTokenResult;

export type VerifyTokenError = VerifyTokenResult;

export type VerifyTokenParams = {
    token?: string;
};
