'use client';

import type React from 'react';
import { useEffect, useState } from 'react';

import type { MonitorErrors } from '@/types/monitor.type';

export function useInputErrors(markInputAsTouched: React.Dispatch<React.SetStateAction<string>>) {
    const [errors, setErrors] = useState<MonitorErrors>({});

    const resetInputError = (name: string) => {
        setErrors((prev) => ({ ...prev, [name]: undefined }));
    };

    useEffect(() => {
        Object.entries(errors).forEach(([key, error]) => !error || markInputAsTouched(key));
    }, [errors, markInputAsTouched]);

    return { errors, setErrors, resetInputError };
}
