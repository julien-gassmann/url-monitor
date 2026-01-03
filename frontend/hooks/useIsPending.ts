'use client';

import { useEffect, useState } from 'react';

import { onPendingApiCall } from '@/lib/events/PendingApiCallEvent';

export function useIsPending(callId: string, render: boolean) {
    const [isPending, setIsPending] = useState(render);

    useEffect(() => {
        return onPendingApiCall((id: string, pending: boolean) => {
            if (id === callId) {
                setIsPending(pending);
            }
        });
    }, [callId]);

    return isPending;
}
