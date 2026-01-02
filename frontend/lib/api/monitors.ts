import { appToast } from '@/lib/toast';
import type {
    CreateMonitorPayload,
    MonitorErrors,
    MonitorResponse,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

import type { ApiResponse } from './client';
import { apiPost } from './client';

const baseUrl = '/monitors';

export async function validateMonitorField(data: ValidateMonitorPayload) {
    const url = `${baseUrl}/validate`;
    return apiPost<ValidateMonitorPayload, MonitorResponse, MonitorErrors>(url, data).then(
        handleMonitorResponse
    );
}

export async function createMonitor(data: CreateMonitorPayload) {
    return apiPost<CreateMonitorPayload, MonitorResponse, MonitorErrors>(baseUrl, data).then(
        handleMonitorResponse
    );
}

function handleMonitorResponse(response: ApiResponse<MonitorResponse, MonitorErrors>) {
    switch (response.status) {
        case 200:
            break;
        case 201:
            appToast.monitor.success();
            break;
        case 422:
            break;
        default:
            appToast.monitor.failure();
    }

    return response;
}
