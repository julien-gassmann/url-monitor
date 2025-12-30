import type {
    CreateMonitorErrors,
    CreateMonitorPayload,
    MonitorResponse,
} from '@/types/monitor.type';

import { apiPost } from './client';

export async function createMonitor(data: CreateMonitorPayload) {
    return apiPost<CreateMonitorPayload, MonitorResponse, CreateMonitorErrors>('/monitors', data);
}

// export async function getMonitorByToken(token: string) {
//     return apiFetch<Monitor>(`/monitors/${token}`);
// }
