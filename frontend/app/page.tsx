import CreateMonitorForm from '@/components/CreateMonitorForm';

export default async function Home() {
    return (
        <main className="min-h-screen bg-violet-100 py-12 px-4 flex items-center justify-center">
            <div
                className="
                w-full
                sm:w-5/6
                md:w-3/4
                lg:w-2/3
                xl:w-1/2
                2xl:w-1/3
            "
            >
                <CreateMonitorForm />
            </div>
        </main>
    );
}
