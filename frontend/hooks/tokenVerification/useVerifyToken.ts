import { useEffect, useState } from 'react';

import { useRouter } from 'next/navigation';

import { verifyAccessToken } from '@/lib/api/token';
import { appToast } from '@/lib/toast';
import type { VerifyTokenResult } from '@/types/token.type';

const initialResult: VerifyTokenResult = {
    is_valid: false,
    reason: null,
    monitor_uuid: null,
    bearer: {
        token: null,
        expires_at: null,
    },
};

export function useVerifyToken(token?: string) {
    const router = useRouter();
    const [result, setResult] = useState<VerifyTokenResult>(initialResult);

    useEffect(() => {
        verifyAccessToken(token).then((response) => {
            const { ok, data, errors } = response;

            if (ok && data) {
                // Handle success
                setResult(data);

                localStorage.setItem('bearer_token', data.bearer.token as string);
                router.push(`/monitor/${data.monitor_uuid}`);
                appToast.verification.success();
            }

            if (errors) {
                // Handle failure
                setResult(errors);
                appToast.verification.failure();
            }
        });
    }, [token, router]);

    return result;
}
