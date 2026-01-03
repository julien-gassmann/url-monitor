'use client';

import React, { useEffect, useState } from 'react';

import { useMetadata } from '@/hooks/monitorForm/useMetadata';
import { onMonitorCreatedEvent } from '@/lib/events/monitorCreatedEvent';
import type { CreateMonitorPayload } from '@/types/monitor.type';
import { LuCircleCheckBig } from 'react-icons/lu';

import CreateMonitorForm from '@/components/CreateMonitorForm';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

const initialPayload: CreateMonitorPayload = {
    url: '',
    expected_http_code: 200,
    frequency: '',
    user_email: '',
};

export default function Home() {
    const metadata = useMetadata();
    const [payload, setPayload] = useState<CreateMonitorPayload>(initialPayload);
    const [monitorCreated, setMonitorCreated] = useState<boolean>(false);

    useEffect(() => {
        return onMonitorCreatedEvent(() => {
            setMonitorCreated(true);
            setPayload(initialPayload);
        });
    }, []);

    return (
        <div className=" w-full sm:w-5/6 md:w-3/4 lg:w-2/3 xl:w-1/2 2xl:w-1/3">
            <CollapseTransition show={metadata !== null && !monitorCreated}>
                <CreateMonitorForm metadata={metadata} payload={payload} setPayload={setPayload} />
            </CollapseTransition>

            <CollapseTransition show={monitorCreated}>
                <div className="p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
                    <div className="text-md space-y-6 ">
                        <div className="flex items-center justify-center gap-2">
                            <LuCircleCheckBig className="text-emerald-400 size-7" />
                            <p className="font-bold">La surveillance a été créée avec succès.</p>
                        </div>
                        <div>
                            <p>Vous venez de recevoir un mail avec le premier résultat.</p>
                            <p>
                                Pour créer une nouvelle surveillance, veuillez cliquer sur le bouton
                                ci-dessous :
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
