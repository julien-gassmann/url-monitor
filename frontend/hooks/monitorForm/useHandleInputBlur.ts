import type { FocusEvent } from 'react';
import type React from 'react';

import { validateMonitorField } from '@/lib/api/monitors';
import type {
    CreateMonitorPayload,
    MonitorErrors,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

export function useHandleInputBlur(
    payload: CreateMonitorPayload,
    setErrors: React.Dispatch<React.SetStateAction<MonitorErrors>>,
    resetInputError: React.Dispatch<React.SetStateAction<string>>
) {
    return async (e: FocusEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;

        // Avoid API call if input is empty
        if (!value) {
            resetInputError(name);
            return;
        }

        // Filter formData to keep only filled fields
        const filteredData = Object.fromEntries(
            Object.entries(payload).filter(([_key, data]) => Boolean(data))
        ) as ValidateMonitorPayload;

        // Validate field and fill errors on 422
        const response = await validateMonitorField(filteredData);
        if (response.status === 422) {
            setErrors(response.errors);
        }
    };
}
