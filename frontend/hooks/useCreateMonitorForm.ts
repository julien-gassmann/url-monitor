import {ChangeEvent, useEffect, useState} from "react";
import {CreateMonitorErrors, CreateMonitorPayload} from "@/types/monitor.type";
import {createMonitor} from "@/lib/api/monitors";
import {getMetadata, MetadataResponse} from "@/lib/metadata";
import {appToast} from "@/lib/toast";

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
    const [metadata, setMetadata] = useState<MetadataResponse | null>(null);

    useEffect(() => {
        getMetadata().then(response =>
            response.ok && response.data
                ? setMetadata(response.data)
                : appToast.error('Impossible de charger les métadonnées.')
        );
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

        switch (response.status) {
            case 201: appToast.monitor.created(); break
            case 422: setErrors(response.errors); break
            default: appToast.monitor.failed()
        }

        setIsSubmitting(false);
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