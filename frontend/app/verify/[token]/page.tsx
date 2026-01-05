'use client';

import React from 'react';

import { useParams, useRouter } from 'next/navigation';

import { useVerifyToken } from '@/hooks/tokenVerification/useVerifyToken';
import { useIsPending } from '@/hooks/useIsPending';
import type { VerifyTokenParams } from '@/types/token.type';
import { PiWarningCircle } from 'react-icons/pi';

import { CircularLoader } from '@/components/ui/CircularLoader';

export default function Verify() {
    const router = useRouter();
    const { token } = useParams<VerifyTokenParams>();
    const result = useVerifyToken(token);
    const isLoading = useIsPending('verify-token', true);

    const handleRedirect = () => router.push(`/refresh/${token}`);

    if (isLoading) {
        return <CircularLoader />;
    }

    return (
        <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
            {!result.is_valid && (
                <div className="relative overflow-hidden p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-xl shadow-lg">
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
                        onClick={handleRedirect}
                        className=" w-1/2 py-2 px-4 bg-black text-white font-bold hover:bg-violet-700 hover:cursor-pointer rounded-xl"
                    >
                        Recevoir un nouveau lien
                    </button>
                </div>
            )}
        </div>
    );
}
