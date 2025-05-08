# Template Sections Refactoring Guide

## Overview

This document provides guidance on the refactoring process to simplify the template system by:

1. Removing the `template_sections` table
2. Moving the section information directly into the `template_fields` table via a `section_name` column
3. Simplifying the API and frontend code to work with section names directly

## Motivation

The template section system had an unnecessary level of complexity. By directly using section names in the template fields:

- We reduce database queries and joins
- We simplify the data model
- We make the API more straightforward
- We remove the need to manage a separate table for sections

## Migration Steps

### Database Changes

1. Execute the migration script `remove_template_sections.sql` to:
   - Add a `section_name` column to `template_fields`
   - Copy section names from `template_sections` to `template_fields`
   - Set 'Default' for any null section names
   - Create an index on the new column for performance

2. After verifying the data migration was successful:
   - Uncomment and run the command to drop the `section_id` column from `template_fields`
   - Uncomment and run the command to drop the `template_sections` table

### Backend API Changes

The API endpoints for template sections have been updated to maintain backward compatibility while using the new data model:

- `GET /templates/{template_id}/sections` - Returns sections based on unique section names from fields
- `POST /templates/{template_id}/sections` - Creates a virtual section without actually creating a database entry
- `PUT /templates/sections/{section_id}` - Updates section_name in fields that use it
- `DELETE /templates/sections/{section_id}` - Resets fields to "Default" section

### Frontend Changes

1. The template models have been updated:
   - `TemplateField` now uses `section_name` instead of `section_id`
   - `TemplateSection` uses a string ID based on a hash of the section name

2. The UI components have been updated to:
   - Use section names directly instead of section IDs
   - Generate consistent section IDs from section names
   - Handle section management through the field's section_name property

## Implementation Notes

### Template Service

The `TemplateService.php` has been modified to:
- Remove JOIN operations with the template_sections table
- Work directly with section names in template fields
- Generate virtual sections based on unique section names

### Template Frontend Components

The `TemplateFieldEditor.vue` has been updated to:
- Work with section names instead of section IDs
- Generate consistent section IDs from names
- Group fields by section name directly

## Testing

After applying these changes, you should test:

1. Template creation and editing
2. Adding and editing fields with sections
3. Creating new sections
4. Moving fields between sections
5. Viewing templates with sections properly grouped
6. Document generation based on templates with sections

## Rollback Plan

If issues are encountered, you can roll back the changes by:

1. Restoring the original template_sections table
2. Recreating the section_id column in template_fields
3. Rebuilding the relationships based on section names
4. Reverting the API and frontend code changes

A backup of the relevant database tables should be made before applying the migration.
