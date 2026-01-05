'use client';

import React, { useEffect, useRef } from 'react';

import { useParams } from 'next/navigation';

import { useRefreshToken } from '@/hooks/tokenVerification/useRefreshToken';
import { useIsPending } from '@/hooks/useIsPending';
import type { VerifyTokenParams } from '@/types/token.type';
import { HiOutlineMail } from 'react-icons/hi';
import { PiWarningCircle } from 'react-icons/pi';

import { CircularLoader } from '@/components/ui/CircularLoader';

export default function Verify() {
    const { token } = useParams<VerifyTokenParams>();
    const { tokenIsRefreshed, handleAccessTokenRefresh } = useRefreshToken(token);
    const isLoading = useIsPending('refresh-token', false);
    const hasRefreshed = useRef(false);

    useEffect(() => {
        if (!hasRefreshed.current) {
            handleAccessTokenRefresh().then();
            hasRefreshed.current = true;
        }
    }, [handleAccessTokenRefresh]);

    if (isLoading) {
        return <CircularLoader />;
    }

    if (tokenIsRefreshed) {
        return (
            <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
                <div className="p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
                    <div className="flex items-center gap-2">
                        <HiOutlineMail className="text-emerald-400" size={30} />
                        <p className="font-bold">Un nouveau lien de vérification a été envoyé.</p>
                    </div>

                    <p>Ce lien restera valide 5 minutes à compter de la reception du mail.</p>
                </div>
            </div>
        );
    } else {
        return (
            <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
                <div className="p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
                    <div className="flex items-center gap-2">
                        <PiWarningCircle className="text-red-400" size={30} />
                        <p className="font-bold">{"Le lien n'est plus valable."}</p>
                    </div>

                    <p>
                        Cliquez sur le bouton <strong>renouveler</strong> du dernier mail reçu pour
                        obtenir un nouveau lien de vérification.
                    </p>
                </div>
            </div>
        );
    }
}
