import { emitMonitorCreatedEvent } from '@/lib/events/monitorCreatedEvent';
import { appToast } from '@/lib/toast';
import type {
    CreateMonitorPayload,
    MonitorErrors,
    MonitorResponse,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

import type { ApiResponse } from './client';
import { apiGet, apiPost } from './client';

const baseUrl = '/monitors';

export async function validateMonitorField(data: ValidateMonitorPayload) {
    const url = `${baseUrl}/validate`;
    const callId = 'validate-monitor';
    return apiPost<ValidateMonitorPayload, MonitorResponse, MonitorErrors>(url, callId, data).then(
        handleMonitorResponse
    );
}

export async function createMonitor(data: CreateMonitorPayload) {
    const callId = 'create-monitor';
    return apiPost<CreateMonitorPayload, MonitorResponse, MonitorErrors>(
        baseUrl,
        callId,
        data
    ).then(handleMonitorResponse);
}

export async function getMonitor(uuid: string) {
    const url = `${baseUrl}/${uuid}`;
    const callId = 'get-monitor';
    return apiGet<MonitorResponse, []>(url, callId).then(handleMonitorResponse);
}

function handleMonitorResponse(response: ApiResponse<MonitorResponse, MonitorErrors | []>) {
    switch (response.status) {
        case 200:
            break;
        case 201:
            appToast.monitor.success();
            emitMonitorCreatedEvent();
            break;
        case 422:
            break;
        default:
            appToast.monitor.failure();
    }

    return response;
}
