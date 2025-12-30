import React, { useRef, useEffect, useState } from "react";

type CollapseProps = {
    show: boolean
    children: React.ReactNode
}

export function CollapseTransition({ show, children }: CollapseProps) {
    const contentRef = useRef<HTMLDivElement|null>(null);
    const [height, setHeight] = useState<number>(0);

    useEffect(() => {
        if (contentRef.current) {
            const newHeight = contentRef.current.scrollHeight;
            setHeight(newHeight);
        }
    }, [children]);

    return (
        <div
            style={{
                maxHeight: show ? `${height * 2}px` : '0px',
                opacity: show ? 1 : 0,
            }}
            className="transition-all duration-300 ease-in-out overflow-hidden"
        >
            <div ref={contentRef}>
                {children}
            </div>
        </div>
    );
}