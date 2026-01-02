import { type ChangeEvent, type FocusEvent, useEffect, useState } from 'react';

import type { ApiResponse } from '@/lib/api/client';
import { createMonitor, validateMonitorField } from '@/lib/api/monitors';
import { type MetadataResponse, getMetadata } from '@/lib/api/metadata';
import { appToast } from '@/lib/toast';
import type {
    CreateMonitorPayload,
    MonitorErrors,
    MonitorResponse,
    ValidateMonitorPayload,
} from '@/types/monitor.type';

const initialMonitor: CreateMonitorPayload = {
    url: '',
    expected_http_code: 200,
    frequency: '',
    user_email: '',
};

export function useCreateMonitorForm() {
    const [formData, setFormData] = useState<CreateMonitorPayload>(initialMonitor);
    const [errors, setErrors] = useState<MonitorErrors>({});
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [metadata, setMetadata] = useState<MetadataResponse | null>(null);

    useEffect(() => {
        getMetadata()
            .then((response) =>
                response.ok && response.data
                    ? setMetadata(response.data)
                    : appToast.error('Impossible de charger les métadonnées.')
            );
    }, []);

    // ------------------- Event Handlers -------------------

    const handleChange = (e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        const typedValue = name === 'expected_http_code' ? parseInt(value) || 0 : value;
        resetFieldError(name);
        setFormData((prev) => ({ ...prev, [name]: typedValue }));
    };

    const handleBlur = async (e: FocusEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        resetFieldError(name);

        // Avoid API call if input is empty
        if (!value) {
            return;
        }

        // Filter formData to keep only filled fields
        const filteredData = Object.fromEntries(
            Object.entries(formData).filter(([_key, data]) => Boolean(data))
        );

        validateMonitorField(filteredData as ValidateMonitorPayload)
            .then((response) => {
                handleResponse(response);
            })
    };

    const handleCloseError = (name: string) => resetFieldError(name);

    const handleSubmit = async () => {
        setIsSubmitting(true);
        setErrors({});

        createMonitor(formData)
            .then((response) => {
                handleResponse(response);
                setIsSubmitting(false);
            });
    };

    // ------------------- Helpers -------------------

    // Used for CreateMonitor and ValidateMonitorField API calls
    const handleResponse = (response: ApiResponse<MonitorResponse, MonitorErrors>) => {
        switch (response.status) {
            case 200:
                break;
            case 201:
                appToast.monitor.created();
                break;
            case 422:
                setErrors(response.errors);
                break;
            default:
                appToast.monitor.failed();
        }
    };

    const resetFieldError = (name: string) => {
        setErrors((prev) => ({ ...prev, [name]: undefined }));
    };

    return {
        metadata,
        formData,
        errors,
        isSubmitting,
        handleChange,
        handleBlur,
        handleCloseError,
        handleSubmit,
    };
}
