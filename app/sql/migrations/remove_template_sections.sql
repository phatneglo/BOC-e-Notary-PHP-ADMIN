-- Migration script to remove template_sections table and update template_fields
-- This script should be executed carefully as it modifies the schema and data

-- Step 1: Add section_name column to template_fields if it doesn't exist
DO $$
BEGIN
  IF NOT EXISTS (
    SELECT FROM information_schema.columns 
    WHERE table_name = 'template_fields' AND column_name = 'section_name'
  ) THEN
    ALTER TABLE template_fields ADD COLUMN section_name VARCHAR(255);
  END IF;
END
$$;

-- Step 2: Copy section names from template_sections to template_fields
UPDATE template_fields tf
SET section_name = ts.section_name
FROM template_sections ts
WHERE tf.section_id = ts.section_id;

-- Step 3: Set 'Default' for any NULL section_name
UPDATE template_fields
SET section_name = 'Default'
WHERE section_name IS NULL;

-- Step 4: Create an index on section_name for performance
CREATE INDEX IF NOT EXISTS idx_template_fields_section_name ON template_fields(section_name);

-- Step 5: Remove the section_id foreign key constraint if it exists
DO $$
BEGIN
  IF EXISTS (
    SELECT FROM information_schema.table_constraints 
    WHERE constraint_name = 'fk_template_fields_section_id' 
    AND table_name = 'template_fields'
  ) THEN
    ALTER TABLE template_fields DROP CONSTRAINT fk_template_fields_section_id;
  END IF;
END
$$;

-- Step 6: Drop the section_id column from template_fields
-- NOTE: Only do this after confirming all section_name values are properly set
-- ALTER TABLE template_fields DROP COLUMN section_id;

-- Step 7: Drop the template_sections table
-- NOTE: Only do this after confirming all data has been migrated
-- DROP TABLE template_sections;

-- Commented out the most destructive operations - they should be run manually
-- after verifying the migration was successful
