-- FAQ Categories and Items - Revised for User Process Flow
-- Focuses on concrete user steps and common questions at each stage

-- Insert FAQ categories aligned with user process flow
INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Getting Started', 'Questions about account creation and initial setup', 1, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Getting Started');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Template Management', 'Questions about creating and managing document templates', 2, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Template Management');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Document Submission', 'Questions about completing and submitting documents for notarization', 3, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Document Submission');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Payment', 'Questions about payment methods and process', 4, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Payment');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Document Status', 'Questions about checking document status and notifications', 5, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Document Status');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Modifications', 'Questions about modifying rejected documents', 6, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Modifications');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Notarized Documents', 'Questions about accessing and downloading notarized documents', 7, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Notarized Documents');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Verification', 'Questions about verifying document authenticity', 8, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Verification');

-- Getting Started FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Getting Started' LIMIT 1),
    'How do I create an account?',
    'To create an account, click the "Create an Account" button on the homepage. You will be presented with a Privacy Notice in compliance with the Data Privacy Act that you must agree to before proceeding. After agreeing, you will need to complete the application form with your personal information, attach a government-issued ID, and create your digital signature. Once your application is complete, your account will be activated and you can begin using the E-Notary services.',
    1,
    TRUE,
    'account creation, registration, signup, new account'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I create an account?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Getting Started' LIMIT 1),
    'What information do I need to provide during registration?',
    'During registration, you will need to provide: 1) Personal information including your full name, contact details, and address, 2) A valid government-issued ID that must be uploaded to the system, and 3) Your digital signature which can be created directly within the system. All information is securely stored in compliance with the Data Privacy Act.',
    2,
    TRUE,
    'registration information, required documents, personal information, ID requirement'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What information do I need to provide during registration?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Getting Started' LIMIT 1),
    'How do I create my digital signature?',
    'During the registration process, you will be prompted to create your digital signature. You can either: 1) Use the signature pad feature to draw your signature using your mouse or touchscreen, 2) Upload an image of your signature, or 3) Use the system''s typing tool to create a signature based on text input. This digital signature will be used for all your e-notarization requests.',
    3,
    TRUE,
    'digital signature, signature creation, sign documents, electronic signature'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I create my digital signature?');

-- Template Management FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Template Management' LIMIT 1),
    'How do I access my templates?',
    'To access your templates, first login to your account. From the E-Notary Dashboard, click on the "My Template" section. This will display all your existing templates if you have any. If you do not have any templates yet, you will see an option to add new templates.',
    1,
    TRUE,
    'templates, my templates, access templates, dashboard, template section'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I access my templates?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Template Management' LIMIT 1),
    'How do I add a new template?',
    'To add a new template, navigate to the "My Template" section of your Dashboard and click the "Add" button. You will be presented with two options: 1) Select from available system templates, or 2) Upload your own document. Choose the option that suits your needs, then follow the on-screen prompts to complete the process. The template will be saved to your account for future use.',
    2,
    TRUE,
    'add template, new template, upload document, create template'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I add a new template?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Template Management' LIMIT 1),
    'Can I use my own document as a template?',
    'Yes, you can upload your own document to use as a template. From the "My Template" section, click the "Add" button and select the "Upload Document" option. The system accepts PDF files and certain document formats. Once uploaded, your document will be available in your template list for future use. Note that all documents must comply with e-notarization requirements.',
    3,
    TRUE,
    'custom template, upload document, own document, document format'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Can I use my own document as a template?');

-- Document Submission FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Submission' LIMIT 1),
    'How do I submit a document for e-notarization?',
    'To submit a document for e-notarization: 1) Go to your Dashboard and click on "My Template", 2) Select the template you wish to use, 3) The system will auto-fill information from your registration, 4) Complete any remaining required fields, 5) Attach any supporting documents as needed, 6) Review the Application Summary, 7) Confirm your application, and 8) Proceed to payment. After payment, your document will be submitted to a notary public for review.',
    1,
    TRUE,
    'submit document, e-notarization process, document submission, notarization request'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I submit a document for e-notarization?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Submission' LIMIT 1),
    'What supporting documents may I need to attach?',
    'Required supporting documents vary depending on the document type being notarized. Common supporting documents include: 1) Identification documents of all parties involved, 2) Proof of authorization if acting on behalf of someone else, 3) Relevant certificates or licenses, and 4) Any reference documents mentioned in the main document. The system will indicate which supporting documents are required during the submission process.',
    2,
    TRUE,
    'supporting documents, attachments, required documents, document requirements'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What supporting documents may I need to attach?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Submission' LIMIT 1),
    'How do I review my document before submission?',
    'Before final submission, you will be shown an Application Summary page that displays all the information you''ve entered and documents you''ve attached. Carefully review this summary to ensure all information is correct and complete. If you need to make changes, click the "Back" or "Edit" button to return to the form. Once you confirm that everything is correct, click the "Confirm" button to proceed with your submission.',
    3,
    TRUE,
    'review document, application summary, confirm submission, check document'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I review my document before submission?');

-- Payment FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'How do I pay for my e-notarization request?',
    'After confirming your document submission, the system will generate a payment reference number and direct you to the payment page. Here, you can select your preferred payment merchant from the available options. Follow the prompts to complete your transaction through the selected payment channel. Once payment is complete, you will receive a digital receipt via email, and your document will be placed in the notary queue.',
    1,
    TRUE,
    'payment process, make payment, payment methods, payment step'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I pay for my e-notarization request?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'What payment methods are accepted?',
    'The E-Notary system accepts various payment methods through our partner payment merchants. These typically include: 1) Credit/debit cards (Visa, Mastercard, etc.), 2) Online banking, 3) Mobile payment apps, 4) E-wallets, and 5) Over-the-counter banking options. Available payment methods will be displayed on the payment selection page after document submission.',
    2,
    TRUE,
    'payment options, payment methods, credit card, online banking, e-wallet'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What payment methods are accepted?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'Will I get a receipt for my payment?',
    'Yes, once your payment is successfully processed, the system will automatically send a digital receipt to your registered email address. This receipt includes your payment reference number, transaction details, and the amount paid. You can also access your payment history and receipts from the Dashboard. Keep this receipt for your records as proof of payment.',
    3,
    TRUE,
    'payment receipt, transaction record, email receipt, proof of payment'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Will I get a receipt for my payment?');

-- Document Status FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'How do I check the status of my document?',
    'You can check the status of your document at any time by logging into your account and clicking on the "Inbox" menu. This will display all your submitted documents along with their current status. The status indicators include: Draft, Submitted, Payment Pending, In Queue, Under Review, Notarized, or Rejected. You will also receive email notifications when there are significant status changes.',
    1,
    TRUE,
    'check status, document tracking, status updates, inbox'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I check the status of my document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'How will I be notified about my document status?',
    'The system will automatically send notifications to your registered email address at key stages of the process: 1) When your payment is confirmed, 2) When a notary begins reviewing your document, 3) When your document is notarized, or 4) If your document is rejected. You can also opt-in for SMS notifications in your account settings. Additionally, you can check your document status anytime by logging into your account.',
    2,
    TRUE,
    'notifications, email updates, status alerts, SMS notification'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How will I be notified about my document status?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'How long does it take for my document to be notarized?',
    'The typical processing time for document notarization is 1-2 business days after payment confirmation. Documents are processed on a first-in, first-out basis by available notary publics. Processing times may vary depending on current system volume and document complexity. You can always check the current status of your document through your account Inbox.',
    3,
    TRUE,
    'processing time, waiting period, notarization timeline, turnaround time'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How long does it take for my document to be notarized?');

-- Modifications FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Modifications' LIMIT 1),
    'What should I do if my document is rejected?',
    'If your document is rejected, you will receive a notification with the rejection reason. To modify a rejected document: 1) Login to your account and go to the "Inbox" menu, 2) Locate the rejected document and click the "Modify" button, 3) The system will display your application with the notary''s comments, 4) Make the necessary corrections or add required documents, 5) Review your changes, and 6) Resubmit the document. No additional payment is required for resubmission.',
    1,
    TRUE,
    'rejected document, document correction, modify document, resubmission'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What should I do if my document is rejected?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Modifications' LIMIT 1),
    'Do I need to pay again if my document is rejected?',
    'No, you do not need to pay again for rejected documents. When a document is rejected, the system preserves your payment information. After making the necessary corrections, you can resubmit the document without any additional payment. The corrected document will go directly back into the notary queue for review.',
    2,
    TRUE,
    'payment for rejected documents, free resubmission, payment preservation, correction cost'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Do I need to pay again if my document is rejected?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Modifications' LIMIT 1),
    'How can I see why my document was rejected?',
    'When your document is rejected, you will receive an email notification explaining the reason. Additionally, you can view the rejection details by: 1) Logging into your account, 2) Going to the "Inbox" menu, 3) Finding your rejected document (marked with a "Rejected" status), and 4) Clicking on the document to view the detailed comments provided by the notary public explaining why the document was rejected and what corrections are needed.',
    3,
    TRUE,
    'rejection reason, document comments, notary feedback, correction guidance'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I see why my document was rejected?');

-- Notarized Documents FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notarized Documents' LIMIT 1),
    'How do I access my notarized documents?',
    'To access your notarized documents: 1) Login to your account, 2) Click on the "Notarized Document" menu in the Dashboard, 3) You will see a list of all your successfully notarized documents, 4) Click on any document to view its details and the notarized version. From this page, you can view, download, or verify your notarized documents.',
    1,
    TRUE,
    'access documents, view notarized documents, document location, find documents'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I access my notarized documents?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notarized Documents' LIMIT 1),
    'How do I download my notarized document?',
    'To download a notarized document: 1) Navigate to the "Notarized Document" menu in your Dashboard, 2) Select the document you wish to download from the list, 3) On the document details page, click the "Download" button, 4) The document will automatically download as a PDF file. The downloaded document includes all notarization elements such as the notary seal, signature, and verification QR code.',
    2,
    TRUE,
    'download document, save document, PDF download, get document copy'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I download my notarized document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notarized Documents' LIMIT 1),
    'How long can I access my notarized documents in the system?',
    'Notarized documents remain accessible in your account for 5 years from the date of notarization. We recommend downloading and saving important documents to your personal storage as a backup. If you need access to documents beyond this period, please contact our support team with your specific document details and reference numbers.',
    3,
    TRUE,
    'document retention, access period, document storage, archive policy'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How long can I access my notarized documents in the system?');

-- Verification FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'How can I verify a notarized document using the website?',
    'To verify a document via website: 1) Open your web browser and go to the E-Notary verification page, 2) Locate the DOC number and keycode on the notarized document (usually found at the bottom of the document or near the notary seal), 3) Enter these details in the designated fields on the verification page, 4) Click the "Verify" button. If the information is correct, the system will display the e-notary image confirming authenticity. If incorrect, an error message will appear.',
    1,
    TRUE,
    'website verification, verify online, document authentication, check document'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I verify a notarized document using the website?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'How can I verify a notarized document using the QR code?',
    'To verify a document using the QR code: 1) Use a smartphone or tablet with a QR code scanner or camera, 2) Scan the QR code printed on the notarized document, 3) The scan will direct you to the E-Notary verification page, 4) Follow the instructions on-screen, which will typically ask you to confirm the DOC number and keycode, 5) Click "Verify" to complete the verification. The system will display the authentication result immediately.',
    2,
    TRUE,
    'QR code verification, scan QR, mobile verification, code scanning'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I verify a notarized document using the QR code?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'What happens if verification fails multiple times?',
    'For security purposes, the system limits verification attempts. After three consecutive failed verification attempts from the same source, the document verification will be blocked for 24 hours. This security measure prevents unauthorized attempts to guess verification credentials. If you''re having trouble verifying a document, double-check the DOC number and keycode for accuracy, and ensure you''re entering them exactly as they appear on the document, including any special characters or spaces.',
    3,
    TRUE,
    'failed verification, security lockout, verification limits, verification errors'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What happens if verification fails multiple times?');

-- Additional General FAQs (Can be placed at the end of the script)
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Getting Started' LIMIT 1),
    'What is the E-Notary system?',
    'The E-Notary system is a comprehensive digital platform that enables paperless notarization of documents. It allows users to create accounts, submit documents for notarization, make online payments, and receive legally notarized digital documents. The system provides a secure end-to-end process that includes document verification capabilities through both website and QR code scanning. All notarizations are performed by authorized notary publics and comply with relevant laws and regulations.',
    4,
    TRUE,
    'e-notary system, digital notarization, electronic notary, system overview'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What is the E-Notary system?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Getting Started' LIMIT 1),
    'Is E-Notary legally valid?',
    'Yes, documents notarized through the E-Notary system are legally valid. The system complies with the Electronic Commerce Act (Republic Act No. 8792) and other relevant laws governing electronic documents and digital signatures in the Philippines. E-notarized documents can be verified for authenticity through the system''s verification features, providing an additional layer of security and legal validity.',
    5,
    TRUE,
    'legal validity, document legality, electronic notarization laws, legal compliance'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Is E-Notary legally valid?');
