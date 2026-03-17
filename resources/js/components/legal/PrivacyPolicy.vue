<template>
    <div class="min-h-screen bg-linear-to-b from-amber-50 via-white to-white flex flex-col">
        <!-- Back to home -->
        <div class="relative z-10 px-6 pt-6">
            <router-link
                to="/"
                class="inline-flex items-center gap-2 text-stone-600 hover:text-amber-600 transition-colors font-medium"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to home
            </router-link>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex-1 max-w-3xl mx-auto px-6 py-12">
            <h1 class="font-serif text-4xl font-bold text-stone-800 mb-2">
                Privacy Policy
            </h1>
            <p class="text-sm text-stone-400 mb-8">
                Last updated: March 12, 2026
            </p>

            <div class="prose prose-stone max-w-none space-y-6">
                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        1. Information We Collect
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        We collect information you provide when creating an account (such as your name and email address) and data you enter while using the service (such as your repertoire and practice logs).
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        2. How We Use Your Information
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        Your information is used solely to provide and improve the Piano Piece Manager service. We do not sell your personal data to third parties.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        3. Data Storage and Security
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        We take reasonable measures to protect your data. Your information is stored securely and access is limited to authorized personnel only.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        4. Third-Party Services
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        If you sign in with Google, we receive basic profile information (name and email) from Google. We do not access any other Google account data.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        5. Your Rights
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        You may request access to, correction of, or deletion of your personal data at any time by contacting us.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        6. Changes to This Policy
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        We may update this Privacy Policy from time to time. We will notify you of any significant changes by posting the updated policy on this page.
                    </p>
                </section>

                <section>
                    <h2 class="font-serif text-xl font-semibold text-stone-800 mb-3">
                        7. Contact
                    </h2>
                    <p class="text-stone-600 leading-relaxed">
                        If you have any questions about this Privacy Policy, please contact us.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>
