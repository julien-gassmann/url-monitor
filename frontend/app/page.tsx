'use client';

import React, { useEffect, useState } from 'react';

import { useMetadata } from '@/hooks/monitorForm/useMetadata';
import { useIsPending } from '@/hooks/useIsPending';
import { onMonitorCreatedEvent } from '@/lib/events/monitorCreatedEvent';
import type { CreateMonitorPayload } from '@/types/monitor.type';
import { LuCircleCheckBig } from 'react-icons/lu';

import MonitorCreationForm from '@/components/MonitorCreationForm';
import { CircularLoader } from '@/components/ui/CircularLoader';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

const initialPayload: CreateMonitorPayload = {
    url: '',
    expected_http_code: 200,
    frequency: '',
    user_email: '',
};

export default function Home() {
    const metadata = useMetadata('create-monitor');
    const [payload, setPayload] = useState<CreateMonitorPayload>(initialPayload);
    const [monitorCreated, setMonitorCreated] = useState<boolean>(false);
    const isMetadataLoading = useIsPending('get-metadata', true);

    useEffect(() => {
        return onMonitorCreatedEvent(() => {
            setMonitorCreated(true);
            setPayload(initialPayload);
        });
    }, []);

    if (isMetadataLoading) {
        return <CircularLoader />;
    }

    return (
        <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
            <CollapseTransition show={metadata !== null && !monitorCreated}>
                <MonitorCreationForm
                    metadata={metadata}
                    payload={payload}
                    setPayload={setPayload}
                />
            </CollapseTransition>

            <CollapseTransition show={monitorCreated}>
                <div className="m-6 p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-3xl shadow-lg">
                    <div className="text-md space-y-6 ">
                        <div className="flex items-center justify-center gap-2">
                            <LuCircleCheckBig className="text-emerald-400 size-7" />
                            <p className="font-bold">La surveillance a été créée avec succès.</p>
                        </div>
                        <div className="flex flex-col items-center space-y-2">
                            <p>Vous venez de recevoir un mail avec le premier résultat.</p>
                            <p>
                                Pour créer une nouvelle surveillance, veuillez cliquer sur le bouton
                                ci-dessous.
                            </p>
                        </div>
                    </div>

                    <button
                        onClick={() => setMonitorCreated(false)}
                        className=" w-1/2 py-2 px-4 bg-black text-white font-bold hover:bg-violet-700 hover:cursor-pointer rounded-xl"
                    >
                        Nouvelle surveillance
                    </button>
                </div>
            </CollapseTransition>
        </div>
    );
}
