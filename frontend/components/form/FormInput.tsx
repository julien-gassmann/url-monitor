import React, { type ChangeEvent } from 'react';

import type { IconType } from 'react-icons';

import { FormErrorMessage } from '@/components/form/FormErrorMessage';

type FormInputProps = {
    name: string;
    value: string | number;
    error?: string[];
    label?: string;
    placeholder?: string;
    prependIcon?: IconType;
    onChange: (_e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => void;
    onErrorClose: (_name: string) => void;
};

export function FormInput({
    name,
    value,
    error,
    label,
    placeholder,
    prependIcon,
    onChange,
    onErrorClose,
}: FormInputProps) {
    const PrependIcon: IconType = prependIcon as IconType;

    return (
        <div>
            <label className="block text-sm font-bold text-black mb-2">{label}</label>

            <div className="relative">
                {PrependIcon && !value && (
                    <PrependIcon
                        className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                        size={18}
                    />
                )}

                <input
                    placeholder={placeholder}
                    name={name}
                    value={value}
                    onChange={onChange}
                    className={`w-full px-3 py-2 
                        bg-gray-100
                        hover:bg-violet-100 hover:cursor-pointer
                        focus-within:outline-2 focus-within:outline-violet-700
                        rounded-md
                        ${value ? 'text-black' : 'placeholder-gray-500'}
                        ${prependIcon && !value ? 'pl-10' : ''}
                        ${error ? 'outline-1 outline-red-400' : ''}`}
                />
            </div>

            <FormErrorMessage messages={error} onClose={() => onErrorClose(name)} />
        </div>
    );
}
