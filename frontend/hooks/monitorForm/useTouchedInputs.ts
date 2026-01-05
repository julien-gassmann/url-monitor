'use client';

import { useCallback, useState } from 'react';

import type { TouchedMonitorPayload } from '@/types/monitor.type';

const initialTouchedInputs: TouchedMonitorPayload = {
    url: false,
    expected_http_code: false,
    frequency: false,
    user_email: false,
};

export function useTouchedInputs() {
    const [touchedInput, setTouchedInput] = useState<TouchedMonitorPayload>(initialTouchedInputs);

    const markInputAsTouched = useCallback((name: string, touched: boolean = true) => {
        setTouchedInput((prev) => ({ ...prev, [name]: touched }));
    }, []);

    return { touchedInput, markInputAsTouched };
}
