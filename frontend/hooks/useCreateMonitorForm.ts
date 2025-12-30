import {ChangeEvent, useEffect, useState} from "react";
import {CreateMonitorErrors, CreateMonitorPayload} from "@/types/monitor.type";
import {createMonitor} from "@/lib/api/monitors";
import {isApiError} from "@/lib/api/client";
import {ApiMetadata, getMetadata} from "@/lib/metadata";

const initialMonitor: CreateMonitorPayload = {
    url: '',
    expected_http_code: 200,
    frequency: '',
    user_email: '',
};

export function useCreateMonitorForm() {
    const [formData, setFormData] = useState<CreateMonitorPayload>(initialMonitor);
    const [errors, setErrors] = useState<CreateMonitorErrors>({});
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [metadata, setMetadata] = useState<ApiMetadata | null>(null);

    useEffect(() => {
        getMetadata().then(setMetadata);
    }, []);

    const handleChange = (e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        const typedValue = name === 'expected_http_code' ? parseInt(value) || 0 : value

        setErrors(prev => ({ ...prev, [name]: undefined }));
        setFormData(prev => ({ ...prev, [name]: typedValue}));
    };

    const handleCloseError = (name: keyof CreateMonitorErrors) => {
        setErrors(prev => ({ ...prev, [name]: undefined }));
    }

    const handleSubmit = async () => {
        setIsSubmitting(true);
        setErrors({});

        const response = await createMonitor(formData);

        if (isApiError(response)) {
            setErrors(response.errors);
        }

        setIsSubmitting(false);

        return ! isApiError(response)
    };

    return {
        metadata,
        formData,
        errors,
        isSubmitting,
        handleChange,
        handleCloseError,
        handleSubmit
    };
}