@extends('marketing.layout.app', ['title' => __('Dashboard')])
@section('content')
    <main class="grow">

        <!-- Intro -->
        <section>
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="pt-32 pb-12 md:pt-40 md:pb-20">

                    <!-- Section header -->
                    <div class="max-w-3xl mx-auto text-center pb-12 md:pb-16">
                        <h1 class="h1 mb-4">Privacy Policy</h1>
                        <p class="text-xl text-gray-600">
                            Our Commitment to Safeguarding Your Privacy and Confidentiality
                        </p>
                    </div>


                </div>
            </div>
        </section>

        <!-- Story -->
        <section>
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
                <div class="pb-12 md:pb-20">

                    <div class="max-w-3xl mx-auto">
                        <p>At OpenChat, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and store your data when you use our product, OpenChat. Please take the time to read this policy carefully to understand our practices regarding your personal information.</p>

                        <h2>1. Information We Collect</h2>

                        <h3>1.1 Personal Information</h3>
                        <p>When you sign up for OpenChat, we may collect certain personal information, including but not limited to:</p>
                        <ul>
                            <li class="mb-2">Your name</li>
                            <li class="mb-2">Email address</li>
                            <li class="mb-2">Profile picture (optional)</li>
                            <li class="mb-2">User-generated content (messages, posts, and other communications)</li>
                        </ul>

                        <h3>1.2 Usage Information</h3>
                        <p>We also gather usage information when you interact with OpenChat, such as:</p>
                        <ul>
                            <li class="mb-2">IP addresses</li>
                            <li class="mb-2">Device information (e.g., operating system, browser type)</li>
                            <li class="mb-2">Log data (e.g., access times, pages viewed, actions taken within the app)</li>
                        </ul>

                        <h2>2. How We Use Your Information</h2>

                        <h3>2.1 Providing and Improving OpenChat</h3>
                        <p>We utilize your personal information to:</p>
                        <ul>
                            <li class="mb-2">Enable you to create and manage your OpenChat account</li>
                            <li class="mb-2">Facilitate communication and interactions within OpenChat</li>
                            <li class="mb-2">Personalize your user experience</li>
                            <li class="mb-2">Improve our product, including adding new features and functionality</li>
                        </ul>

                        <h3>2.2 Communication and Updates</h3>
                        <p>We may use your email address to send you important notifications, updates about OpenChat, and relevant promotional materials. You can opt-out of marketing communications at any time.</p>

                        <h2>3. Information Sharing and Disclosure</h2>

                        <h3>3.1 Third-Party Service Providers</h3>
                        <p>We may engage trusted third-party service providers to assist us in operating, maintaining, and improving OpenChat. These providers are bound by confidentiality obligations and are only authorized to use your personal information as necessary to provide services to us.</p>

                        <h3>3.2 Legal Requirements</h3>
                        <p>We may disclose your personal information if required to do so by law or if we believe that such action is necessary to:</p>
                        <ul style="">
                            <li class="mb-2">Comply with legal obligations</li>
                            <li class="mb-2">Protect our rights, property, or safety</li>
                            <li class="mb-2">Investigate and prevent potential violations</li>
                        </ul>

                        <h2>4. Data Security</h2>
                        <p>We implement security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, please be aware that no method of transmission over the internet or electronic storage is 100% secure.</p>

                        <h2>5. Data Retention</h2>
                        <p>We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law.</p>

                        <h2>6. Your Rights and Choices</h2>
                        <p>You have the right to:</p>
                        <ul>
                            <li class="mb-2">Access, update, or delete your personal information</li>
                            <li class="mb-2">Object to the processing of your personal information</li>
                            <li class="mb-2">Withdraw consent for certain processing activities</li>
                        </ul>

                        <h2>7. Children's Privacy</h2>
                        <p>OpenChat is not intended for use by individuals under the age of 13. We do not knowingly collect personal information from children. If we become aware that we have collected personal information from a child without parental consent, we will take steps to remove that information from our servers.</p>

                        <h2>8. Changes to this Privacy Policy</h2>
                        <p>We may update this Privacy Policy from time to time. We will notify you of any significant changes by email or through a notice on the OpenChat website or app.</p>

                        <h2>9. Contact Us</h2>
                        <p>If you have any questions, concerns, or requests regarding this Privacy Policy or the processing of your personal information, please contact us at <a href="mailto:contact@openchat.com">contact@openchat.com</a>.</p>

                        <p>Last Updated: [Date]</p>

                        <p>Note: This privacy policy template is provided as a general guide. Depending on the specific nature of your product and jurisdiction, you may need to tailor the policy to meet your unique requirements and comply with applicable laws and regulations. It is always recommended to seek legal advice to ensure compliance.</p>
                    </div>

                    <div class="max-w-3xl mx-auto">
                        <p class="text-lg text-gray-600">
                            Aenean sed adipiscing diam donec adipiscing tristique risus nec feugiat auctor urna nunc id cursus metus aliquam eleifend, arcu dictum varius duis at consectetur lorem donec massa sapien, sed risus ultricies tristique nulla aliquet. Morbi tristique senectus et netus et, nibh nisl condimentum id venenatis a condimentum vitae sapien.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Cta -->
        <section>
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="pb-12 md:pb-20">

                    <!-- CTA box -->
                    <div class="bg-gray-900 rounded py-10 px-8 md:py-16 md:px-12 shadow-2xl" data-aos="zoom-y-out">

                        <div class="flex flex-col lg:flex-row justify-between items-center">

                            <!-- CTA content -->
                            <div class="mb-6 lg:mr-16 lg:mb-0 text-center lg:text-left lg:w-1/2">
                                <h3 class="h3 text-white">Build your own ChatGPT for free!</h3>
                            </div>

                            <!-- CTA button -->
                            <div>
                                <a class="btn text-white bg-blue-600 hover:bg-blue-700" href="{{route('marketing.register')}}">Build your chatbot</a>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </main>
@endsection

@section('scripts')
@endsection
