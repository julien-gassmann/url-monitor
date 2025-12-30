import { apiPost } from './client';
import {CreateMonitorErrors, CreateMonitorPayload, MonitorRaw} from "@/types/monitor.type";

export async function createMonitor(data: CreateMonitorPayload) {
    return apiPost<CreateMonitorPayload, MonitorRaw, CreateMonitorErrors>('/monitors', data);
}

// export async function getMonitorByToken(token: string) {
//     return apiFetch<Monitor>(`/monitors/${token}`);
// }