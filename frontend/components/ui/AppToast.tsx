import React from 'react';

import { Toaster } from 'react-hot-toast';

export const AppToast = () => (
    <Toaster
        position="top-right"
        toastOptions={{
            duration: 5000,
            className: 'text-black',
            success: {
                iconTheme: {
                    primary: 'var(--color-emerald-400)',
                    secondary: 'white',
                },
            },
            error: {
                iconTheme: {
                    primary: 'var(--color-red-400)',
                    secondary: 'white',
                },
            },
        }}
    />
);
