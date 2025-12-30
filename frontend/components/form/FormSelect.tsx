import {FormErrorMessage} from "@/components/form/FormErrorMessage";
import React, {ChangeEvent} from "react";
import {LuChevronDown} from "react-icons/lu";

type FormSelectProps = {
    label?: string
    name: string
    value: string|number
    defaultOption?: string
    error?: string[]
    onChange: (e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => void
    onErrorClose: (name: string) => void
    children?: React.ReactNode
}

export function FormSelect({ label, name, value, defaultOption, error, onChange, onErrorClose, children }: FormSelectProps) {
    return (
        <div>
            <label className="block text-sm font-bold text-black mb-2">
                {label}
            </label>

            <div className="relative">
                <select
                    name={name}
                    value={value}
                    onChange={onChange}
                    className={
                        `w-full px-3 py-2
                        bg-gray-100
                        hover:bg-violet-100 hover:cursor-pointer
                        focus-within:outline-2 focus-within:outline-violet-700
                        appearance-none
                        rounded-md
                        ${value ? 'text-black' : 'text-gray-500'}
                        ${error ? 'outline-1 outline-red-400' : ''}`
                    }
                >
                    {defaultOption &&
                        <option value="">
                            {defaultOption}
                        </option>
                    }

                    {children}
                </select>

                <LuChevronDown
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                    size={18}
                />
            </div>

            <FormErrorMessage messages={error} onClose={() => onErrorClose(name)}/>
        </div>
    )
}