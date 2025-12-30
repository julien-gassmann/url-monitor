'use client';

import React from 'react';

import { useCreateMonitorForm } from '@/hooks/useCreateMonitorForm';
import { HiOutlineMail } from 'react-icons/hi';
import { LuCircleCheckBig, LuGlobe } from 'react-icons/lu';

import { FormInput } from '@/components/form/FormInput';
import { FormSelect } from '@/components/form/FormSelect';
import { CollapseTransition } from '@/components/ui/CollapseTransition';

export default function CreateMonitorForm() {
    const {
        metadata,
        formData,
        errors,
        isSubmitting,
        handleChange,
        handleCloseError,
        handleSubmit,
    } = useCreateMonitorForm();

    const onSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await handleSubmit();
    };

    return (
        <CollapseTransition show={metadata !== null}>
            {metadata && (
                <form
                    onSubmit={onSubmit}
                    className="m-6 p-6 space-y-6 bg-white rounded-lg shadow-lg"
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
                        value={formData.url}
                        placeholder={'https://exemple.com'}
                        error={errors.url}
                        onChange={handleChange}
                        onErrorClose={handleCloseError}
                    />

                    {/* Code HTTP attendu */}
                    <FormSelect
                        label={'Code de statut HTTP attendu'}
                        name={'expected_http_code'}
                        value={formData.expected_http_code}
                        defaultOption={'Sélectionnez un code'}
                        error={errors.expected_http_code}
                        onChange={handleChange}
                        onErrorClose={handleCloseError}
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
                        value={formData.frequency}
                        defaultOption={'Sélectionnez une fréquence'}
                        error={errors.frequency}
                        onChange={handleChange}
                        onErrorClose={handleCloseError}
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
                        value={formData.user_email}
                        placeholder={'votre@email.com'}
                        prependIcon={HiOutlineMail}
                        error={errors.user_email}
                        onChange={handleChange}
                        onErrorClose={handleCloseError}
                    />

                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className="
                        w-full flex items-center justify-center gap-4 py-2 px-4
                        bg-black text-white font-bold
                        hover:bg-violet-700 hover:cursor-pointer
                        disabled:opacity-50
                        rounded-md
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
