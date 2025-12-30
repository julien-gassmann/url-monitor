export type MonitorRaw = {
    url: string,
    expected_http_code: number,
    frequency: string,
    user: { email: string },
}

export type CreateMonitorPayload = {
    url: string,
    expected_http_code: number,
    frequency: string,
    user_email: string,
}

export type CreateMonitorErrors = {
    url?: string[],
    expected_http_code?: string[],
    frequency?: string[],
    user_email?: string[],
}