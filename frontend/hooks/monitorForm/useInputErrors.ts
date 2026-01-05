'use client';

import { useEffect, useState } from 'react';

import type { MonitorErrors } from '@/types/monitor.type';

export function useInputErrors(markInputAsTouched: (_name: string, _touched?: boolean) => void) {
    const [errors, setErrors] = useState<MonitorErrors>({});

    const resetInputError = (name: string) => {
        markInputAsTouched(name, false);
        setErrors((prev) => ({ ...prev, [name]: undefined }));
    };

    useEffect(() => {
        Object.entries(errors).forEach(([key, error]) => !error || markInputAsTouched(key, true));
    }, [errors, markInputAsTouched]);

    return { errors, setErrors, resetInputError };
}
