'use client';

import React from 'react';

import { useParams } from 'next/navigation';

import type { VerifyTokenParams } from '@/types/token.type';

export default function Verify() {
    const { uuid } = useParams<VerifyTokenParams>();

    return (
        <div className="w-full sm:w-5/6 md:w-3/4 lg:w-2/3 xl:w-1/2 2xl:w-1/3">
            <p>UUID: {uuid}</p>
        </div>
    );
}
