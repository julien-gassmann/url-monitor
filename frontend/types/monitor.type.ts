import type { PaginationFilters } from '@/types/api.type';

export type Monitor = {
    uuid: string;
    url: string;
    expected_http_code: number;
    frequency: string;
    user: { email: string };
};

export type ValidateMonitorPayload = {
    url?: string;
    expected_http_code?: number;
    frequency?: string;
    user_email?: string;
};

export type CreateMonitorPayload = {
    url: string;
    expected_http_code: number;
    frequency: string;
    user_email: string;
};

export type TouchedMonitorPayload = {
    url: boolean;
    expected_http_code: boolean;
    frequency: boolean;
    user_email: boolean;
};

export type MonitorErrors = {
    url?: string[];
    expected_http_code?: string[];
    frequency?: string[];
    user_email?: string[];
};

export type MonitorCheck = {
    http_code: string;
    status: string;
    checked_at: string;
};

export type AllowedChecksSort =
    | 'http_code'
    | '-http_code'
    | 'status'
    | '-status'
    | 'checked_at'
    | '-checked_at';

export type MonitorCheckFilters = PaginationFilters<AllowedChecksSort, unknown>;
