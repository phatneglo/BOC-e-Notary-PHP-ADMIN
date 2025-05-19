-- FAQ and Support Tables Setup
-- Run this script to add the necessary tables if they don't exist

-- FAQ Categories Table

-- Insert default FAQ categories if none exist
INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'General', 'General questions about the E-Notary system', 1, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories");

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Document Creation', 'Questions about creating documents', 2, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Document Creation');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Notarization', 'Questions about the notarization process', 3, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Notarization');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Verification', 'Questions about verifying documents', 4, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Verification');

INSERT INTO "faq_categories" ("category_name", "description", "display_order", "is_active")
SELECT 'Payment', 'Questions about payment and fees', 5, TRUE
WHERE NOT EXISTS (SELECT 1 FROM "faq_categories" WHERE "category_name" = 'Payment');

-- Insert sample FAQs if none exist
INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'General' LIMIT 1),
    'What is the E-Notary system?',
    'The E-Notary system is a digital platform that allows users to create, submit, and notarize documents electronically. It streamlines the traditional notarization process by eliminating the need for physical presence and paper documents.',
    1,
    TRUE,
    'e-notary, system, overview, digital notarization'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items");

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Document Creation' LIMIT 1),
    'How do I create a new document?',
    'To create a new document, log in to your account, go to the dashboard, and click on "Create New Document". You can then select from available templates or upload your own document. Fill in the required information and follow the prompts to complete the document creation process.',
    1,
    TRUE,
    'document, create, template, upload'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How do I create a new document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Notarization' LIMIT 1),
    'How long does the notarization process take?',
    'The notarization process typically takes 1-2 business days from the time of submission, depending on the current queue and workload of notaries. You will receive notifications at each stage of the process, and you can always check the status of your document in your dashboard.',
    1,
    TRUE,
    'notarization, process, timeline, waiting time'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How long does the notarization process take?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Verification' LIMIT 1),
    'How can I verify a notarized document?',
    'To verify a notarized document, go to the Verification page and enter the document number and keycode provided on the document. Alternatively, you can scan the QR code on the document. The system will display the verification status and basic information about the document.',
    1,
    TRUE,
    'verify, verification, document, authenticity'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'How can I verify a notarized document?');

INSERT INTO "faq_items" ("category_id", "question", "answer", "display_order", "is_active", "tags")
SELECT 
    (SELECT "category_id" FROM "faq_categories" WHERE "category_name" = 'Payment' LIMIT 1),
    'What payment methods are accepted?',
    'The E-Notary system accepts various payment methods including credit/debit cards, online banking, and digital wallets. The available payment options will be displayed at the checkout page when you submit a document for notarization.',
    1,
    TRUE,
    'payment, methods, credit card, online banking'
WHERE NOT EXISTS (SELECT 1 FROM "faq_items" WHERE "question" = 'What payment methods are accepted?');
