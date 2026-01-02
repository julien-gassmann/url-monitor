import { useState } from 'react';

import { refreshAccessToken } from '@/lib/api/token';
import { appToast } from '@/lib/toast';

export function useRefreshToken(token?: string) {
    const [tokenIsRefreshed, setTokenIsRefreshed] = useState<boolean>(false);

    const handleAccessTokenRefresh = async () => {
        const response = await refreshAccessToken(token);
        if (response.ok) {
            setTokenIsRefreshed(true);
            appToast.refresh.success();
        } else {
            appToast.refresh.failure();
        }
    };

    return {
        tokenIsRefreshed,
        handleAccessTokenRefresh,
    };
}
