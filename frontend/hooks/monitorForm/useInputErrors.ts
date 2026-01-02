import { useState } from 'react';

import type { MonitorErrors } from '@/types/monitor.type';

export function useInputErrors() {
    const [errors, setErrors] = useState<MonitorErrors>({});

    const resetInputError = (name: string) => {
        setErrors((prev) => ({ ...prev, [name]: undefined }));
    };

    return { errors, setErrors, resetInputError };
}
