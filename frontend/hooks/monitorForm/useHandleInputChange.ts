'use client';

import type React from 'react';
import { type ChangeEvent } from 'react';

import type { CreateMonitorPayload } from '@/types/monitor.type';

export function useHandleInputChange(
    setPayload: React.Dispatch<React.SetStateAction<CreateMonitorPayload>>,
    markInputAsTouched: (_: string, __?: boolean) => void,
    resetInputError: (_: string) => void
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
