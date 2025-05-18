<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250506130607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add color column and unique index to priority table, ensuring no duplicates.';
    }

    public function up(Schema $schema): void
    {
        // Step 1: Remove duplicate rows based on the title column.
        // First, create a temporary table to hold unique priority entries.
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE temp_priority AS 
            SELECT MIN(id) AS id, title
            FROM priority
            GROUP BY title;
        SQL);

        // Step 2: Delete duplicates from the original priority table by keeping only the rows with ids in the temporary table.
        $this->addSql(<<<'SQL'
            DELETE FROM priority WHERE id NOT IN (SELECT id FROM temp_priority);
        SQL);

        // Step 3: Drop the temporary table now that the duplicates have been removed.
        $this->addSql(<<<'SQL'
            DROP TEMPORARY TABLE IF EXISTS temp_priority;
        SQL);

        // Step 4: Add the color column to the priority table, if it doesn't already exist.
        $this->addSql(<<<'SQL'
            SET @column_exists = (SELECT COUNT(*) 
                                   FROM INFORMATION_SCHEMA.COLUMNS 
                                   WHERE table_name = 'priority' 
                                   AND column_name = 'color');
            IF @column_exists = 0 THEN
                ALTER TABLE priority ADD color VARCHAR(7) DEFAULT NULL;
            END IF;
        SQL);

        // Step 5: Add the unique index to the title column.
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_62A6DC272B36786B ON priority (title);
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Step 1: Drop the unique index on title.
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_62A6DC272B36786B ON priority;
        SQL);

        // Step 2: Remove the color column from the priority table, if it exists.
        $this->addSql(<<<'SQL'
            SET @column_exists = (SELECT COUNT(*) 
                                   FROM INFORMATION_SCHEMA.COLUMNS 
                                   WHERE table_name = 'priority' 
                                   AND column_name = 'color');
            IF @column_exists > 0 THEN
                ALTER TABLE priority DROP color;
            END IF;
        SQL);
    }
}
