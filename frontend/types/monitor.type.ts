export type MonitorResponse = {
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
