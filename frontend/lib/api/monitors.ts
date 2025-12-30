import { apiPost } from './client';
import {CreateMonitorErrors, CreateMonitorPayload, MonitorResponse} from "@/types/monitor.type";

export async function createMonitor(data: CreateMonitorPayload) {
    return apiPost<CreateMonitorPayload, MonitorResponse, CreateMonitorErrors>('/monitors', data);
}

// export async function getMonitorByToken(token: string) {
//     return apiFetch<Monitor>(`/monitors/${token}`);
// }