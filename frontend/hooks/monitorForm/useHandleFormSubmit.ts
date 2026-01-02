'use client';

import type React from 'react';
import { useState } from 'react';

import { createMonitor } from '@/lib/api/monitors';
import type { CreateMonitorPayload, MonitorErrors } from '@/types/monitor.type';

export function useHandleFormSubmit(
    payload: CreateMonitorPayload,
    setErrors: React.Dispatch<React.SetStateAction<MonitorErrors>>
) {
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    const handleSubmit = async () => {
        setIsSubmitting(true);
        setErrors({});

        const response = await createMonitor(payload);
        if (response.status === 422) {
            setErrors(response.errors);
        }

        setIsSubmitting(false);
    };

    return { isSubmitting, handleSubmit };
}
