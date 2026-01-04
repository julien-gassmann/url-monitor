'use client';

import type React from 'react';
import { useEffect, useState } from 'react';

import { getMonitorChecks } from '@/lib/api/monitors';
import type { Pagination } from '@/types/api.type';
import type { MonitorCheck, MonitorCheckFilters } from '@/types/monitor.type';

const initialPagination: Pagination<MonitorCheck> = {
    data: [],
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    links: {} as any,
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    meta: {} as any,
};

const initialFilters: MonitorCheckFilters = {
    page: 1,
    per_page: 10,
    sort: 'checked_at',
};

export function useMonitorChecks(
    uuid: string,
    setUnauthorized: React.Dispatch<React.SetStateAction<boolean>>
) {
    const [pagination, setPaginatedChecks] = useState<Pagination<MonitorCheck>>(initialPagination);
    const [filters, setFilters] = useState<MonitorCheckFilters>(initialFilters);

    useEffect(() => {
        getMonitorChecks(uuid, filters).then((response) => {
            if (response.status === 401) {
                setUnauthorized(true);
            }

            if (response.ok) {
                const { data, links, meta } = response as Pagination<MonitorCheck>;
                setPaginatedChecks({ data, links, meta });
            }
        });
    }, [uuid, filters, setUnauthorized]);

    return { pagination, filters, setFilters };
}
