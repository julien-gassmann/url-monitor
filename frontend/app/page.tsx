import CreateMonitorForm from '@/components/CreateMonitorForm';

export default async function Home() {
    return (
        <main className="min-h-screen bg-violet-100 py-12 px-4 flex items-center justify-center">
            <div
                className="
                w-full
                sm:w-3/4
                md:w-2/3
                lg:w-1/2
                xl:w-1/2
            "
            >
                <CreateMonitorForm />
            </div>
        </main>
    );
}
