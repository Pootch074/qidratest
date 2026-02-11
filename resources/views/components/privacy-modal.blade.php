<div id="termsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

        <div class="text-sm text-gray-600 max-h-60 overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">
                User Service Agreement
            </h2>

            This User Service Agreement (“Agreement”) is a legal agreement between the Department of Social
            Welfare and Development Field Office XI ("Department") and you ("User") governing your access to and
            use of the Queueing Information for DSWD Request Assistance System (the "System"). The System is
            designed to manage and organize client flow for the Department, specifically under the Crisis
            Intervention Section (CIS)

            Acceptance of Terms
            By accessing or using the System, you agree to be bound by this Agreement and any additional terms
            and conditions that may apply to specific features of the System. If you do not agree to these
            terms, you may not access or use the System.

            Description of the System
            The System is part of the Department's Digital Transformation efforts, aimed at streamlining the
            queuing process to minimize waiting time and eliminate manual record handling. It enables clients to
            obtain queue numbers for regular, priority, and returnee beneficiaries, monitor their status in
            real-time, and receive updates on the progress of their service. For administrative users, the
            system provides tools to configure service steps and windows, manage queues, and monitor operations
            for improved control and efficiency.

            User Responsibilities
            As a User of the System, you are responsible for the following:
            Accurate Data Collection: You must collect and record data accurately and completely, following the
            guidelines and protocols provided by us.
            Confidentiality: You must maintain the confidentiality of all data collected through the System and
            protect it from unauthorized access, use, or disclosure.
            Compliance with Laws: You must comply with all applicable laws and regulations, including data
            privacy laws, when using the System.
            Proper Use: You must use the System only for its intended purpose and in accordance with this
            Agreement.
            Account Security: If you are provided with an account, you are responsible for maintaining the
            security of your login credentials and for all activities that occur under your account.
            Reporting Errors: You must promptly report any errors, bugs, or other issues you encounter while
            using the System to us.
            Cooperation: You must cooperate with our reasonable requests for information or assistance related
            to your use of the System.

            Data Privacy
            Data Collection and Use: The collection and use of data through the System are governed by our
            Privacy Notice, which is incorporated into this Agreement by reference. You agree to review the
            Privacy Notice to understand how data is collected, used, and protected.
            Beneficiaries’ Data: You acknowledge that the System will be used to collect data related to
            children. You represent and warrant that you will obtain all necessary consents from parents or
            guardians before collecting such data, in accordance with applicable laws and regulations.

            Disclaimer of Warranty
            The System is provided "as is" and "as available," without any warranties of any kind, express or
            implied, including warranties of merchantability, fitness for a particular purpose, or
            non-infringement. We do not warrant that the System will be error-free, uninterrupted, or secure.

            Termination
            We may terminate your access to the System at any time, with or without cause, and without prior
            notice. Upon termination, you must cease all use of the System and destroy any copies of any data or
            materials you have obtained through the System.

            Changes to this Agreement
            We may update this Agreement from time to time. We will notify you of any material changes by
            posting the updated Agreement on the System or by other reasonable means. Your continued use of the
            System after the effective date of the updated Agreement constitutes your acceptance of the changes.

            Entire Agreement
            This Agreement constitutes the entire agreement between you and us regarding your use of the System
            and supersedes all prior or contemporaneous agreements and understandings, whether oral or written.

            Contact Information
            If you have any questions or concerns about this Agreement, please contact us at:

            RICTMS, DSWD Field Office XI
            XDC, 4th Floor, Diamond Bldg
            Ramon Magsaysay Ave. cor. D. Suazo St., Davao City
            icts.fo11@dswd.gov.ph
            (082) 227-1964 local 1146

            By accessing or using the System, you acknowledge that you have read, understood, and agree to be
            bound by this User Service Agreement.

            <br><br>

            <h2 class="text-lg font-semibold mb-4">
                Privacy Notice
            </h2>

            What Information Do We Collect
            Upon user registration for the system, the following information will be collected:
            Full Name – for identification and ensuring accountability when using the system
            Email – Email must be provided for direct communication and support.
            Position – to properly assign the user's role and responsibilities within the system
            This system will also collect the following information from clients:
            Full Name of the person in the queue.
            How Do We Collect the Information
            During the registration process, users will personally enter their information into the system.
            Personal information will be gathered directly from clients, and only authorized individuals are
            permitted to enter the information into the system.

            How We Use Your Information
            The system collects and processes personal data of users and clients. The information collected is
            used for the following purposes:
            Identification: The names of the registered users in the system will be utilized for authorization
            purposes. The names of the clients will be used for logging purposes.
            Contact information: Email of the user will be used for
            Who Has Access to Your Information
            Personal data collected through the queueing system is not shared with external third parties,
            except when required by law, lawful order of a court or regulatory authority, or in compliance with
            government audit and oversight requirements.
            Internal access is limited to authorized personnel on a need-to-know basis and subject to role-based
            access controls.
            How Long Do We Keep Your Information
            Agency Personnel Data: Retained only for as long as necessary for system administration, audit, and
            security purposes, or as required by applicable records management policies.

            Client Full Names: Retained only for the duration necessary to complete daily queue operations and
            are regularly purged or anonymized thereafter.

            Rights of Data Subjects
            In accordance with the Data Privacy Act of 2012, data subjects have the right to:
            Right to Access: To request access to their own personal information.
            Right to Correct: To request that inaccurate or incomplete information be corrected.
            Right to Delete: To request the deletion of their personal information, subject to legal
            limitations.
            Right to Object: To object to the processing of their personal information, subject to legal
            limitations.
            Right to Withdraw Consent: To withdraw their consent to the processing of their personal
            information, where consent is the basis for processing.

            How to Contact Us
            If you have questions, concerns, or requests regarding your personal data, you can reach out to our
            Regional Compliance Officer for Privacy (RCOP):
            DSWD FO XI Regional Office
            Ramon Magsaysay Avenue corner Damaso Suazo Street, Davao City, Philippines 8000
            Tel. No.:(082) 227-1964
            Email: cop.fo11@dswd.gov.ph

        </div>

        <div class="mt-6 flex justify-end">
            <button id="closeTermsModal" class="px-4 py-2 text-sm text-white bg-blue-600 rounded">
                Close
            </button>
        </div>
    </div>
</div>
@section('scripts')
    <script>
        document.getElementById('openTermsModal').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // extra safety
            document.getElementById('termsModal').classList.remove('hidden');
        });

        document.getElementById('closeTermsModal').addEventListener('click', function() {
            document.getElementById('termsModal').classList.add('hidden');
        });
    </script>
@endsection
