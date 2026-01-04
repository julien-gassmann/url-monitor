'use client';

import React from 'react';

import { useParams } from 'next/navigation';

import { useRefreshToken } from '@/hooks/tokenVerification/useRefreshToken';
import { useVerifyToken } from '@/hooks/tokenVerification/useVerifyToken';
import { useIsPending } from '@/hooks/useIsPending';
import type { VerifyTokenParams } from '@/types/token.type';
import { HiOutlineMail } from 'react-icons/hi';
import { PiWarningCircle } from 'react-icons/pi';

import { CircularLoader } from '@/components/ui/CircularLoader';
import { TopLoadingBar } from '@/components/ui/TopLoadingBar';

export default function Verify() {
    const { token } = useParams<VerifyTokenParams>();
    const result = useVerifyToken(token);
    const { tokenIsRefreshed, handleAccessTokenRefresh } = useRefreshToken(token);
    const isVerifyLoading = useIsPending('verify-token', true);
    const isRefreshLoading = useIsPending('refresh-token', false);

    if (isVerifyLoading) {
        return <CircularLoader />;
    }

    return (
        <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
            {!tokenIsRefreshed && !result.is_valid && (
                <div className="relative overflow-hidden p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
                    <TopLoadingBar isLoading={isRefreshLoading} />

                    <div className="text-md space-y-6 ">
                        <div className="flex items-center justify-center gap-2">
                            <PiWarningCircle className="text-red-400 size-7" />
                            <p className="font-bold">{result.reason}</p>
                        </div>
                        <p>
                            Pour recevoir un nouveau lien de vérification, veuillez cliquer sur le
                            bouton ci-dessous.
                        </p>
                    </div>

                    <button
                        onClick={handleAccessTokenRefresh}
                        className=" w-1/2 py-2 px-4 bg-black text-white font-bold hover:bg-violet-700 hover:cursor-pointer rounded-xl"
                    >
                        Recevoir un nouveau lien
                    </button>
                </div>
            )}

            {tokenIsRefreshed && (
                <div className="p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
                    <div className="flex items-center gap-2">
                        <HiOutlineMail className="text-emerald-400" size={30} />
                        <p className="font-bold">Un nouveau lien de vérification a été envoyé.</p>
                    </div>

                    <p>Ce lien restera valide 5 minutes à compter de la reception du mail.</p>
                </div>
            )}
        </div>
    );
}
