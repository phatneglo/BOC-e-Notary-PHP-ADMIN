# FAQ and Support System Implementation Guide

This guide covers the implementation of the FAQ and Support Center backend endpoints for the E-Notary system.

## Overview

The implementation includes:

1. FAQ Management
   - Retrieving FAQ categories
   - Fetching FAQs by category
   - Searching FAQs

2. Support Request System
   - Submitting support requests
   - Checking request status by reference number

## API Endpoints

### FAQ Endpoints

1. **Get FAQ Categories**
   - **URL**: `/api/faqs/categories`
   - **Method**: `GET`
   - **Response**: List of FAQ categories with their details

2. **Get FAQs by Category**
   - **URL**: `/api/faqs/categories/{categoryId}`
   - **Method**: `GET`
   - **Params**: `categoryId` (path parameter)
   - **Response**: List of FAQs for the specified category

3. **Search FAQs**
   - **URL**: `/api/faqs/search?query={searchQuery}`
   - **Method**: `GET`
   - **Params**: `query` (query parameter)
   - **Response**: List of FAQs matching the search query

### Support Endpoints

1. **Submit Support Request**
   - **URL**: `/api/support/contact`
   - **Method**: `POST`
   - **Body**:
     ```json
     {
       "name": "John Doe",
       "email": "john@example.com",
       "subject": "Document Issue",
       "message": "I'm having trouble with my document submission.",
       "request_type": "technical" // Optional (default: "general")
     }
     ```
   - **Response**: Success confirmation with reference number

2. **Check Request Status**
   - **URL**: `/api/support/status/{referenceNumber}`
   - **Method**: `GET`
   - **Params**: `referenceNumber` (path parameter)
   - **Response**: Request status details including history

## Database Setup

The implementation relies on the following database tables:

1. `FAQ_CATEGORIES`: Stores FAQ categories
2. `FAQ_ITEMS`: Stores individual FAQ entries
3. `SUPPORT_REQUESTS`: Stores support requests
4. `SUPPORT_REQUEST_HISTORY`: Tracks the history of status changes for support requests

A setup script is provided at `app/sql/faq_support_setup.sql` to create these tables and insert initial sample data.

## Implementation Details

### FAQ Service (`FaqService.php`)

The FAQ service handles:
- Retrieving all active FAQ categories
- Fetching FAQs for a specific category
- Searching FAQs using keywords

Key features:
- View count tracking: Each time a FAQ is viewed or appears in search results, its view count is incremented
- Keyword search: The search function splits the query into keywords for better search results
- Relevance sorting: Search results are sorted by relevance, with exact matches appearing first

### Support Service (`SupportService.php`)

The Support service handles:
- Creating new support requests
- Generating unique reference numbers
- Retrieving request status and history

Key features:
- Reference number generation: Unique reference numbers are created in the format `BOC-YYYYMMDD-XXXXX`
- Request history tracking: Each status change is recorded in the history table
- IP and user agent tracking: Captures basic information about the request origin for security

## Frontend Integration

The backend endpoints are designed to integrate seamlessly with the existing frontend components:

1. `HelpPage.vue`: Uses the FAQ endpoints to display categories and FAQs
2. `SupportCenterPage.vue`: Uses the Support endpoints to submit requests and check status

## Security Considerations

1. Input validation is performed on all user inputs
2. SQL injection protection using `QuotedValue()` for all database queries
3. Transaction handling for multi-step operations to ensure data consistency

## Next Steps

1. Run the database setup script to create the necessary tables:
   ```
   psql -U your_username -d boc_enotary -f app/sql/faq_support_setup.sql
   ```

2. The endpoints are fully implemented and ready to use with the frontend components.

3. Consider adding admin interfaces for managing FAQs and handling support requests.
