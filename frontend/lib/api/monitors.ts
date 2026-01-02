import type {
    CreateMonitorPayload,
    MonitorErrors,
    MonitorResponse,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

import { apiPost } from './client';

const baseUrl = '/monitors';

export async function validateMonitorField(data: ValidateMonitorPayload) {
    const url = `${baseUrl}/validate`;
    return apiPost<ValidateMonitorPayload, MonitorResponse, MonitorErrors>(url, data);
}

export async function createMonitor(data: CreateMonitorPayload) {
    return apiPost<CreateMonitorPayload, MonitorResponse, MonitorErrors>(baseUrl, data);
}
