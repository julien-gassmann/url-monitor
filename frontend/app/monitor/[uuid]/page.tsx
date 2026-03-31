'use client';

import React from 'react';

import { useParams } from 'next/navigation';

import type { TableContextType } from '@/contexts/TableContext';
import { TableProvider } from '@/contexts/TableContext';
import { useMonitor } from '@/hooks/monitorDetails/useMonitor';
import { useMonitorChecks } from '@/hooks/monitorDetails/useMonitorChecks';
import { useIsPending } from '@/hooks/useIsPending';
import { PiPulseBold } from 'react-icons/pi';

import { MonitorChecksTable } from '@/components/MonitorChecksTable';
import MonitorDetailsHeader from '@/components/MonitorDetailsHeader';
import UnauthorizedCard from '@/components/UnauthorizedCard';
import { CircularLoader } from '@/components/ui/CircularLoader';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

export default function Verify() {
    const uuid = useParams().uuid as string;
    const { monitor, unauthorized, setUnauthorized } = useMonitor(uuid);
    const monitorChecks = useMonitorChecks(uuid, setUnauthorized) as TableContextType;
    const isMonitorLoading = useIsPending('get-monitor', true);

    if (isMonitorLoading) {
        return <CircularLoader />;
    }

    if (unauthorized) {
        return <UnauthorizedCard />;
    }

    return (
        <TableProvider value={monitorChecks}>
            <div className="w-full xl:px-20">
                <div className="mx-6 flex items-center gap-4">
                    <PiPulseBold className="text-violet-700" size={50} />
                    <div className="flex flex-col">
                        <h1 className="text-3xl font-semibold">Surveillance URL</h1>
                        <span className="text-xl font-semibold text-gray-500">
                            Consultation en lecture seule
                        </span>
                    </div>
                </div>

                <CollapseTransition show={monitor !== null}>
                    {monitor && <MonitorDetailsHeader monitor={monitor} />}
                </CollapseTransition>

                <CollapseTransition show={monitor !== null}>
                    {monitorChecks.pagination && <MonitorChecksTable />}
                </CollapseTransition>
            </div>
        </TableProvider>
    );
}
