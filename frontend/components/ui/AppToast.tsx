import { Toaster } from "react-hot-toast";
import React from "react";

export const AppToast = () => (
    <Toaster
        position="top-right"
        toastOptions={{
            duration: 5000,
            className: "text-black",
            success: {
                iconTheme: {
                    className: 'bg-emerald-400',
                    primary: 'emerald-400',
                    secondary: 'white',
                },
            },
            error: {
                iconTheme: {
                    className: 'bg-red-400',
                    primary: 'red-400',
                    secondary: 'white',
                },
            },
        }}
    />
);