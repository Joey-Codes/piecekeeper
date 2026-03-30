<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50 via-white to-white flex flex-col">
        <!-- Back to home -->
        <div class="relative z-10 px-6 pt-6">
            <router-link
                to="/"
                class="inline-flex items-center gap-2 text-stone-600 hover:text-amber-600 transition-colors font-medium"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back to home
            </router-link>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex-1 max-w-3xl mx-auto px-6 py-12">
            <h1 class="font-serif text-4xl font-bold text-stone-800 mb-2">
                Terms of Service
            </h1>
            <p class="text-sm text-stone-400 mb-8">
                Last updated: March 12, 2026
            </p>

            <div class="prose prose-stone max-w-none space-y-6">
                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        1. Acceptance of Terms
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        By accessing or using PieceKeeper, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use the service.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        2. Description of Service
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        PieceKeeper is a web application that helps pianists organize their repertoire, track practice sessions, and monitor progress. The service is provided free of charge.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        3. User Accounts
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        4. User Content
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        You retain ownership of any content you submit to the service. By using PieceKeeper, you grant us a limited license to store and display your content as needed to provide the service.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        5. Limitation of Liability
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        PieceKeeper is provided "as is" without warranties of any kind. We are not liable for any damages arising from your use of the service.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        6. Changes to Terms
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        We reserve the right to modify these terms at any time. Continued use of the service after changes constitutes acceptance of the updated terms.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        7. Contact
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        If you have any questions about these Terms of Service, please contact us.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>
