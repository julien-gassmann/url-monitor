import { LuCircleX } from 'react-icons/lu';

import { CollapseTransition } from '@/components/ui/CollapseTransition';

type FormErrorMessageProps = {
    messages?: string[];
    onClose: () => void;
};

export function FormErrorMessage({ messages, onClose }: FormErrorMessageProps) {
    const hasMessages = messages && messages.length > 0;

    return (
        <CollapseTransition show={!!hasMessages}>
            <div className="relative text-sm text-red-400 p-2 mt-2 bg-red-400/10 rounded-lg">
                {messages?.map((msg, index) => (
                    <div key={index}>{msg}</div>
                ))}

                <button
                    type="button"
                    className="absolute top-2 right-2 text-red-400"
                    onClick={onClose}
                >
                    <LuCircleX className="close-error absolute top-1/2 right-1/2 hover:cursor-pointer" />
                </button>
            </div>
        </CollapseTransition>
    );
}
