'use client';

import type React from 'react';
import { type ChangeEvent } from 'react';

import type { CreateMonitorPayload } from '@/types/monitor.type';

export function useHandleInputChange(
    payload: CreateMonitorPayload,
    setPayload: React.Dispatch<React.SetStateAction<CreateMonitorPayload>>,
    markInputAsTouched: React.Dispatch<React.SetStateAction<string>>,
    resetInputError: React.Dispatch<React.SetStateAction<string>>
) {
    return (e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;

        const typedValue =
            name === 'expected_http_code'
                ? parseInt(value) || 0 // Convert value to number for http code
                : value;

        resetInputError(name);
        markInputAsTouched(name);
        setPayload((prev) => ({ ...prev, [name]: typedValue }));
    };
}
