import { useEffect, useState } from 'react';

import { useRouter } from 'next/navigation';

import { verifyAccessToken } from '@/lib/api/token';
import { appToast } from '@/lib/toast';
import type { VerifyTokenResponse } from '@/types/token.type';

const initialResult: VerifyTokenResponse = {
    is_valid: false,
    reason: null,
    monitor_uuid: null,
    access_token: null,
    token_type: null,
    expires_at: null,
};

export function useVerifyToken(token?: string) {
    const router = useRouter();
    const [result, setResult] = useState<VerifyTokenResponse>(initialResult);

    useEffect(() => {
        verifyAccessToken(token).then((response) => {
            const { ok, data, errors } = response;

            if (ok) {
                // Handle success
                setResult(data);

                localStorage.setItem('bearer_token', data.access_token as string);
                router.push(`/monitor/${data.monitor_uuid}`);
                appToast.verification.success();
            } else {
                // Handle failure
                setResult(errors);
                appToast.verification.failure();
            }
        });
    }, [token, router]);

    return result;
}
