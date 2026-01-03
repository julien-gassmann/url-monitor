'use client';
import React from 'react';

type CircularLoaderProps = {
    size?: number;
    thickness?: number;
    colorClass?: string;
    speed?: number;
};

export function CircularLoader({
    size = 40,
    thickness = 4,
    colorClass = 'border-t-violet-700',
    speed = 500,
}: CircularLoaderProps) {
    return (
        <div
            className={`rounded-full border-gray-500/20 ${colorClass}`}
            style={{
                width: size,
                height: size,
                borderWidth: thickness,
                animation: `spin ${speed}ms linear infinite`,
            }}
        />
    );
}
