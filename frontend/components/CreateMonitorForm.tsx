'use client';

import React, { useState } from 'react';

import { useHandleFormSubmit } from '@/hooks/monitorForm/useHandleFormSubmit';
import { useHandleInputBlur } from '@/hooks/monitorForm/useHandleInputBlur';
import { useHandleInputChange } from '@/hooks/monitorForm/useHandleInputChange';
import { useInputErrors } from '@/hooks/monitorForm/useInputErrors';
import { useMetadata } from '@/hooks/monitorForm/useMetadata';
import type { CreateMonitorPayload } from '@/types/monitor.type';
import { HiOutlineMail } from 'react-icons/hi';
import { LuCircleCheckBig, LuGlobe } from 'react-icons/lu';

import { FormInput } from '@/components/form/FormInput';
import { FormSelect } from '@/components/form/FormSelect';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

const initialPayload: CreateMonitorPayload = {
    url: '',
    expected_http_code: 200,
    frequency: '',
    user_email: '',
};

export default function CreateMonitorForm() {
    const [payload, setPayload] = useState<CreateMonitorPayload>(initialPayload);

    const metadata = useMetadata();
    const { errors, setErrors, resetInputError } = useInputErrors();
    const handleInputChange = useHandleInputChange(payload, setPayload, resetInputError);
    const handleInputBlur = useHandleInputBlur(payload, setErrors, resetInputError);
    const { isSubmitting, handleSubmit } = useHandleFormSubmit(payload, setErrors);

    const onSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await handleSubmit();
    };

    return (
        <CollapseTransition show={metadata !== null}>
            {metadata && (
                <form
                    onSubmit={onSubmit}
                    className="m-6 p-6 space-y-6 bg-white rounded-xl shadow-lg"
                >
                    {/* Form Header */}
                    <div>
                        <div className="flex items-center gap-2 mb-2">
                            <LuGlobe className="stroke-2 text-violet-700 size-5" />
                            <span className="text-md font-bold text-black">
                                Configuration de Surveillance
                            </span>
                        </div>

                        <span className="text-md text-gray-600">
                            Configurez une surveillance pour votre URL et recevez des notifications
                            par email
                        </span>
                    </div>

                    {/* URL */}
                    <FormInput
                        label={'URL à surveiller'}
                        name={'url'}
                        value={payload.url}
                        placeholder={'https://exemple.com'}
                        error={errors.url}
                        onChange={handleInputChange}
                        onBlur={handleInputBlur}
                        onErrorClose={resetInputError}
                    />

                    {/* Code HTTP attendu */}
                    <FormSelect
                        label={'Code de statut HTTP attendu'}
                        name={'expected_http_code'}
                        value={payload.expected_http_code}
                        defaultOption={'Sélectionnez un code'}
                        error={errors.expected_http_code}
                        onChange={handleInputChange}
                        onBlur={handleInputBlur}
                        onErrorClose={resetInputError}
                    >
                        {Object.entries(metadata.http_codes).map(([category, codes]) => (
                            <optgroup key={category} label={category}>
                                {codes.map((code) => (
                                    <option key={code.code} value={code.code}>
                                        {code.code} - {code.message}
                                    </option>
                                ))}
                            </optgroup>
                        ))}
                    </FormSelect>

                    {/* Fréquence */}
                    <FormSelect
                        label={'Période de vérification'}
                        name={'frequency'}
                        value={payload.frequency}
                        defaultOption={'Sélectionnez une fréquence'}
                        error={errors.frequency}
                        onChange={handleInputChange}
                        onBlur={handleInputBlur}
                        onErrorClose={resetInputError}
                    >
                        {metadata.frequencies.map((freq) => (
                            <option key={freq.label} value={freq.label.toLowerCase()}>
                                {freq.label}
                            </option>
                        ))}
                    </FormSelect>

                    {/* Email */}
                    <FormInput
                        label={'Adresse email'}
                        name={'user_email'}
                        value={payload.user_email}
                        placeholder={'votre@email.com'}
                        prependIcon={HiOutlineMail}
                        error={errors.user_email}
                        onChange={handleInputChange}
                        onBlur={handleInputBlur}
                        onErrorClose={resetInputError}
                    />

                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className="
                        w-full flex items-center justify-center gap-4 py-2 px-4
                        bg-black text-white font-bold
                        hover:bg-violet-700 hover:cursor-pointer
                        disabled:opacity-50
                        rounded-xl
                    "
                    >
                        <LuCircleCheckBig />
                        {isSubmitting ? 'Création en cours...' : 'Créer la surveillance'}
                    </button>
                </form>
            )}
        </CollapseTransition>
    );
}
