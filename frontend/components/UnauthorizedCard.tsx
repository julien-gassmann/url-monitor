'use client';

import React from 'react';

import { LuShieldAlert } from 'react-icons/lu';

export default function UnauthorizedCard() {
    return (
        <div className="w-full flex flex-col items-center justify-center min-h-[calc(100vh-6rem)]">
            <div className="p-6 flex flex-col space-y-6 items-center justify-center bg-white rounded-3xl shadow-lg">
                <div className="flex items-center gap-2">
                    <LuShieldAlert className="text-red-400" size={30}/>
                    <p className="font-bold">Accès non authorisé.</p>
                </div>

                <p>{"Votre authorisation d'accès à cette page a expiré."}</p>
                <p>
                    Cliquez sur le bouton <strong>renouveler</strong> du dernier mail reçu pour y
                    accéder de nouveau.
                </p>
            </div>
        </div>
    );
}
