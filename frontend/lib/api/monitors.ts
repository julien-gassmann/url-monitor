import { emitMonitorCreatedEvent } from '@/lib/events/monitorCreatedEvent';
import { appToast } from '@/lib/toast';
import type { ApiResponse, PaginationErrors } from '@/types/api.type';
import type {
    CreateMonitorPayload,
    Monitor,
    MonitorCheck,
    MonitorCheckFilters,
    MonitorErrors,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

import { apiGet, apiPost } from './client';

const baseUrl = '/monitors';

export async function validateMonitorField(data: ValidateMonitorPayload) {
    const url = `${baseUrl}/validate`;
    const callId = 'validate-monitor';
    return apiPost<ValidateMonitorPayload, Monitor, MonitorErrors>(url, callId, data).then(
        (response) => handleMonitor<Monitor, MonitorErrors>(response)
    );
}

export async function createMonitor(data: CreateMonitorPayload) {
    const callId = 'create-monitor';
    return apiPost<CreateMonitorPayload, Monitor, MonitorErrors>(baseUrl, callId, data).then(
        (response) => handleMonitor<Monitor, MonitorErrors>(response)
    );
}

export async function getMonitor(uuid: string) {
    const url = `${baseUrl}/${uuid}`;
    const callId = 'get-monitor';
    return apiGet<Monitor, []>(url, callId).then((response) =>
        handleMonitor<Monitor, []>(response)
    );
}

export async function getMonitorChecks(uuid: string, query: MonitorCheckFilters) {
    const params = new URLSearchParams(Object.entries(query));

    const url = `${baseUrl}/${uuid}/checks?${params.toString()}`;
    const callId = 'get-monitor-checks';

    return apiGet<MonitorCheck[], PaginationErrors<{ message: string }>>(url, callId).then(
        (response) => handleMonitor<MonitorCheck[], PaginationErrors<{ message: string }>>(response)
    );
}

function handleMonitor<R, E>(response: ApiResponse<R, E>) {
    switch (response.status) {
        case 200:
            break;
        case 201:
            appToast.monitor.success();
            emitMonitorCreatedEvent();
            break;
        case 401:
            appToast.monitor.unauthorized();
            break;
        case 422:
            break;
        default:
            appToast.monitor.failure();
    }

    return response;
}
