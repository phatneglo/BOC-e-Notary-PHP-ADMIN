-- FAQ Categories Table - Aligned with E-Notary Process Flow

-- Clear existing data to ensure consistency (optional - remove if you want to preserve existing entries)
-- TRUNCATE TABLE "faq_categories" CASCADE;
-- TRUNCATE TABLE "faq_items" CASCADE;

-- Insert FAQ categories aligned with process flow components
INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'General', 'General questions about the E-Notary system', 1, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'General');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'User Journey', 'Questions about account creation, document creation, and submission', 2, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'User Journey');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Notary Process', 'Questions about how documents are reviewed and notarized', 3, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Notary Process');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Corrections', 'Questions about rejected documents and the correction process', 4, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Corrections');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Verification', 'Questions about verifying notarized documents', 5, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Verification');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Payment', 'Questions about the payment process and options', 6, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Payment');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Document Status', 'Questions about document status and workflow stages', 7, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Document Status');

-- General FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'General' LIMIT 1),
    'What is the E-Notary system?',
    'The E-Notary system is a comprehensive digital platform that allows users to create, submit, and notarize documents electronically. It streamlines the traditional notarization process by eliminating the need for physical presence and paper documents, offering a secure end-to-end workflow from document creation to verification.',
    1,
    TRUE,
    'e-notary, system, overview, digital notarization'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What is the E-Notary system?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'General' LIMIT 1),
    'What are the main steps in the E-Notary process?',
    'The E-Notary system follows six main interconnected processes: 1) User Journey (account creation, document creation, submission), 2) Notary Process (review and digital authentication), 3) Correction Process (for rejected documents), 4) Verification Process (document authentication), 5) Document Status Flow (tracking document stages), and 6) Payment Process (secure online payment). Each component ensures a smooth, paperless notarization experience.',
    2,
    TRUE,
    'steps, process, workflow, overview'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What are the main steps in the E-Notary process?');

-- User Journey FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'User Journey' LIMIT 1),
    'How do I create a new document?',
    'To create a new document: 1) Log in to your account, 2) From your dashboard, click "Create Document", 3) Select a template from the available options, 4) Fill out all required form fields, 5) Review your document for accuracy, and 6) Submit for notarization. The system will guide you through each step of the process, allowing you to save drafts if needed before final submission.',
    1,
    TRUE,
    'document, create, template, submission'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I create a new document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'User Journey' LIMIT 1),
    'What happens after I submit my document for notarization?',
    'After submission, your document status changes to "Pending Payment". You''ll be prompted to select a payment method and complete the transaction. Once payment is successful, the document status updates to "Awaiting Notarization" and enters the notary queue. You''ll receive notifications at each stage, including when your document is approved (notarized) or rejected with correction reasons.',
    2,
    TRUE,
    'submission, process, notification, status updates'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What happens after I submit my document for notarization?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'User Journey' LIMIT 1),
    'How will I know when my document has been notarized?',
    'You will receive an automated notification via email and within the system when your document status changes to "Notarized". You can then log in to view, download, and verify your notarized document. The system also generates a unique document number, keycode, and QR code for future verification purposes.',
    3,
    TRUE,
    'notification, status, completion, notarized document'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How will I know when my document has been notarized?');

-- Notary Process FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notary Process' LIMIT 1),
    'How long does the notarization process take?',
    'The notarization process typically takes 1-2 business days from the time of submission, depending on the current notary queue volume. Documents are processed in a first-in, first-out order with some priority handling available. You can always check the current status in your dashboard.',
    1,
    TRUE,
    'timeline, processing time, queue, waiting'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How long does the notarization process take?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notary Process' LIMIT 1),
    'What does the notary review when examining my document?',
    'Notaries (or authorized AI systems) review your document for completeness, accuracy, compliance with legal requirements, and proper document formatting. They verify that all required fields are completed correctly and that the document meets the standards for electronic notarization. This review ensures your document will be legally valid once notarized.',
    2,
    TRUE,
    'review process, document check, validation, requirements'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What does the notary review when examining my document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notary Process' LIMIT 1),
    'What is included in the digital notarization?',
    'Digital notarization includes: 1) A digital seal applied by an authorized notary, 2) The notary''s electronic signature, 3) A system-generated QR code for verification, 4) A unique document number and keycode, and 5) Secure timestamping. These elements provide legal validity and make the document verifiable through our system.',
    3,
    TRUE,
    'digital seal, signature, QR code, authentication features'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What is included in the digital notarization?');

-- Corrections FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Corrections' LIMIT 1),
    'What happens if my document is rejected?',
    'If your document is rejected, you''ll receive a notification explaining the specific reasons for rejection. Your document status changes to "Rejected", and you can view the detailed rejection reasons in your dashboard. You can then make the necessary corrections and resubmit the document without paying again.',
    1,
    TRUE,
    'rejection, correction, resubmission, notification'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What happens if my document is rejected?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Corrections' LIMIT 1),
    'Do I need to pay again if my document is rejected?',
    'No, you do not need to pay again if your document is rejected. The system preserves your payment information, and when you resubmit the corrected document, it bypasses the payment process and goes directly back into the notary queue. This applies to an unlimited number of correction cycles for the same document.',
    2,
    TRUE,
    'payment preservation, resubmission, no additional cost, correction process'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Do I need to pay again if my document is rejected?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Corrections' LIMIT 1),
    'How do I resubmit a rejected document?',
    'To resubmit a rejected document: 1) Go to your dashboard and locate the rejected document, 2) Click on "View Rejection Reason" to understand what needs to be corrected, 3) Select "Make Corrections" to edit the document, 4) Make the necessary changes, 5) Review the updated document, and 6) Click "Resubmit". The document will return to the notary queue without requiring additional payment.',
    3,
    TRUE,
    'resubmission, correction process, editing, workflow'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I resubmit a rejected document?');

-- Verification FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'How can I verify a notarized document?',
    'There are two ways to verify a notarized document: 1) Visit the Verification page and enter the document number and keycode printed on the document, or 2) Scan the QR code on the document using a smartphone. Both methods will display the document''s verification status and basic information to confirm authenticity.',
    1,
    TRUE,
    'verify, authentication, document number, keycode, QR code'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I verify a notarized document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'Is there a limit to verification attempts?',
    'Yes, for security purposes, there is a limit of three consecutive failed verification attempts using the same IP address. After three failures, verification is locked for 24 hours. This security measure prevents automated attacks or unauthorized verification attempts. The counter resets after a successful verification.',
    2,
    TRUE,
    'security, failed attempts, lockout, verification limits'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Is there a limit to verification attempts?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'Who can verify a notarized document?',
    'Anyone can verify a notarized document through our public verification page - no account or login is required. This allows recipients of your notarized documents to independently verify their authenticity. The verification system only shows basic confirmation that the document is authentic without displaying the full document contents, protecting your privacy.',
    3,
    TRUE,
    'public access, authentication, recipient verification, privacy'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Who can verify a notarized document?');

-- Payment FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'What payment methods are accepted?',
    'The E-Notary system accepts multiple payment methods including credit/debit cards (Visa, Mastercard, American Express), online banking options, digital wallets, and other electronic payment systems. Available payment options are displayed on the payment page after submitting your document for notarization.',
    1,
    TRUE,
    'payment options, credit card, online banking, digital wallet'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What payment methods are accepted?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'How does the payment process work?',
    'The payment process follows these steps: 1) After submitting your document, the system generates a unique payment reference, 2) You select your preferred payment method, 3) You are redirected to a secure payment gateway to complete the transaction, 4) Upon successful payment, the gateway sends a confirmation to our system, 5) Your payment is validated, 6) The system updates your document status to "Payment Completed", 7) A receipt is generated, and 8) Your document moves to "Awaiting Notarization" status.',
    2,
    TRUE,
    'payment flow, transaction, gateway, receipt'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How does the payment process work?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'Can I get a refund if I cancel my notarization request?',
    'Refund policies depend on the document status. If your document is still in "Pending Payment" or "Awaiting Notarization" status, you may request a refund through the support center. Once a document enters "In Review" status, refunds are generally not available as the notarization process has begun. For special circumstances, please contact our support team with your specific case details.',
    3,
    TRUE,
    'refund, cancellation, payment reversal, support'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'Can I get a refund if I cancel my notarization request?');

-- Document Status FAQs
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'What are the different document statuses in the system?',
    'The E-Notary system uses these document status indicators: 1) Draft - document is being created and not yet submitted, 2) Submitted - document has been submitted but payment is not complete, 3) Pending Payment - awaiting payment processing, 4) Payment Completed - payment received but not yet in queue, 5) Awaiting Notarization - in the notary queue, 6) In Review - currently being reviewed by a notary, 7) Notarized - successfully notarized and available for download, 8) Rejected - requires corrections, and 9) Correction - rejected document being updated.',
    1,
    TRUE,
    'status types, workflow states, document lifecycle, tracking'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What are the different document statuses in the system?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'How can I check the status of my document?',
    'You can check your document status at any time by: 1) Logging into your account, 2) Navigating to the dashboard or document list, 3) Locating your document in the list which will display its current status, or 4) Clicking on the document to view detailed status information and history. You will also receive email notifications when your document status changes.',
    2,
    TRUE,
    'status checking, document tracking, notifications, dashboard'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I check the status of my document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Status' LIMIT 1),
    'How long does a document remain in each status?',
    'Document status durations vary: "Draft" remains until you submit (no time limit), "Submitted" and "Pending Payment" remain for up to 7 days before auto-cancellation, "Payment Completed" and "Awaiting Notarization" typically progress within minutes, "In Review" usually lasts 1-2 hours, "Notarized" documents are permanently stored and accessible, "Rejected" documents remain for 30 days before archiving, and "Correction" status has no time limit until you resubmit.',
    3,
    TRUE,
    'timelines, expiration, status duration, processing time'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How long does a document remain in each status?');
