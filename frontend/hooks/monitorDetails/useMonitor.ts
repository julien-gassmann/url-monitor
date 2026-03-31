'use client';

import { useEffect, useState } from 'react';

import { getMonitor } from '@/lib/api/monitors';
import type { Monitor } from '@/types/monitor.type';

export function useMonitor(uuid: string) {
    const [monitor, setMonitor] = useState<Monitor | null>(null);
    const [unauthorized, setUnauthorized] = useState(false);

    useEffect(() => {
        getMonitor(uuid).then((response) => {
            if (response.status === 401) {
                setUnauthorized(true);
            }

            if (response.data) {
                setMonitor(response.data);
            }
        });
    }, [uuid]);

    return { monitor, unauthorized, setUnauthorized };
}
