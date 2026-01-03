'use client';
import { useEffect, useState } from 'react';

type TopLoadingBarProps = {
    isLoading: boolean;
    duration?: number;
};

export function TopLoadingBar({ isLoading, duration = 1000 }: TopLoadingBarProps) {
    const [progress, setProgress] = useState(0);
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        let intervalId: number;
        let timeoutId: number;

        if (isLoading) {
            timeoutId = setTimeout(() => {
                setVisible(true);
                setProgress(10);
            }, 0);

            intervalId = setInterval(() => {
                setProgress((p) => (p >= 90 ? p : Math.min(p + (90 - p) * 0.15, 90)));
            }, duration / 15);
        } else if (visible) {
            timeoutId = setTimeout(() => {
                setProgress(100);
                setTimeout(() => {
                    setVisible(false);
                    setProgress(0);
                }, 100);
            }, 0);
        }

        return () => {
            if (intervalId) {
                clearInterval(intervalId);
            }
            if (timeoutId) {
                clearTimeout(timeoutId);
            }
        };
    }, [isLoading, visible, duration]);

    if (!visible) {
        return null;
    }

    return (
        <div className="absolute top-0 left-0 right-0 h-1 z-50 overflow-hidden pointer-events-none">
            <div
                className="h-full bg-violet-700 origin-left"
                style={{
                    transform: `scaleX(${progress / 100})`,
                    transition: `transform ${
                        progress === 100 ? 100 : 200
                    }ms ${progress === 100 ? 'ease-out' : 'cubic-bezier(0.4, 0, 0.6, 1)'}`,
                }}
            />
        </div>
    );
}
