'use client';

import type React from 'react';

import type { MonitorResponse } from '@/types/monitor.type';
import { LuClock4, LuGlobe } from 'react-icons/lu';

import UrlSnippet from '@/components/ui/UrlSnippet';

type MonitorDetailsHeaderProps = {
    monitor: MonitorResponse;
};

export default function MonitorDetailsHeader({ monitor }: MonitorDetailsHeaderProps) {
    return (
        <div className="m-6 p-6 space-y-10 bg-white rounded-xl font-bold text-gray-500 text-lg shadow-lg">
            {/*Title*/}
            <div className="flex flex-col">
                <div className="flex items-center gap-2 mb-1">
                    <LuGlobe className="text-violet-700" size={25} />
                    <span className="text-xl text-black">Paramètres de configuration</span>
                </div>

                <span>Configuration actuelle de votre surveillance</span>
            </div>

            <div className="flex flex-col gap-8">
                <div className="w-full flex flex-col md:flex-row gap-8">
                    {/*URL*/}
                    <div className="w-full md:w-1/2 flex flex-col gap-2">
                        <span>URL surveillée</span>
                        <UrlSnippet url={monitor.url} />
                    </div>

                    {/*Expected status*/}
                    <div className="w-full md:w-1/2 flex flex-col gap-2">
                        <span>Statut HTTP attendu</span>
                        <span className="text-black">{monitor.expected_http_code}</span>
                    </div>
                </div>

                <div className="w-full flex flex-col md:flex-row gap-8">
                    {/*Frequency*/}
                    <div className="w-full md:w-1/2 flex flex-col gap-2">
                        <span>Fréquence</span>
                        <div className="flex items-center gap-2 mb-1">
                            <LuClock4 size={25} />
                            <span className="text-black">{monitor.frequency}</span>
                        </div>
                    </div>

                    {/*Mail*/}
                    <div className="w-full md:w-1/2 flex flex-col gap-2">
                        <span>Email de notification</span>
                        <span className="text-black">{monitor.user.email}</span>
                    </div>
                </div>
            </div>
        </div>
    );
}
