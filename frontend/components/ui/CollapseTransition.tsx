import React from 'react';

import { AnimatePresence, motion } from 'framer-motion';

type CollapseProps = {
    show: boolean;
    children: React.ReactNode;
};

export function CollapseTransition({ show, children }: CollapseProps) {
    return (
        <AnimatePresence mode="wait" className="p-6">
            {show && (
                <motion.div
                    initial={{ height: 0, opacity: 0 }}
                    animate={{ height: 'auto', opacity: 1 }}
                    exit={{ height: 0, opacity: 0 }}
                    transition={{ duration: 0.2, ease: 'easeInOut' }}
                    style={{ overflow: 'hidden' }}
                >
                    {children}
                </motion.div>
            )}
        </AnimatePresence>
    );
}
