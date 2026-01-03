'use client';

import type React from 'react';

import { validateMonitorField } from '@/lib/api/monitors';
import type {
    CreateMonitorPayload,
    MonitorErrors,
    TouchedMonitorPayload,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

export function useHandleInputBlur(
    payload: CreateMonitorPayload,
    touchedInput: TouchedMonitorPayload,
    setErrors: React.Dispatch<React.SetStateAction<MonitorErrors>>
) {
    return async () => {
        // Filter payload to keep only touched inputs
        const filteredPayload = Object.fromEntries(
            Object.entries(payload).filter(([key, _data]) => touchedInput[key])
        ) as ValidateMonitorPayload;

        // Validate field and fill errors on 422
        const response = await validateMonitorField(filteredPayload);
        if (response.status === 422) {
            setErrors(response.errors);
        }
    };
}
