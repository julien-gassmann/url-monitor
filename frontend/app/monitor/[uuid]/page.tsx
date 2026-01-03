'use client';

import React from 'react';

import { useParams } from 'next/navigation';

import { useMonitor } from '@/hooks/monitorDetails/useMonitor';
import { useIsPending } from '@/hooks/useIsPending';
import type { VerifyTokenParams } from '@/types/token.type';
import { PiPulseBold } from 'react-icons/pi';

import MonitorDetailsHeader from '@/components/MonitorDetailsHeader';
import UnauthorizedCard from '@/components/UnauthorizedCard';
import { CircularLoader } from '@/components/ui/CircularLoader';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

export default function Verify() {
    const { uuid } = useParams<VerifyTokenParams>();
    const { monitor, unauthorized } = useMonitor(uuid);
    const isMonitorLoading = useIsPending('get-monitor', true);

    if (isMonitorLoading) {
        return <CircularLoader />;
    }

    if (unauthorized) {
        return <UnauthorizedCard />;
    }

    return (
        <div className="w-full lg:w-5/6 2xl:w-4/5 space-y-10">
            <div className="flex items-center gap-4">
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

            <p>UUID: {uuid}</p>
        </div>
    );
}
