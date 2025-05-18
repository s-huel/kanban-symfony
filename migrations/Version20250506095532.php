<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250506095532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix priority table and ensure valid references without altering existing columns.';
    }

    public function up(Schema $schema): void
    {
        // Create the priority table only if it doesn't already exist
        $this->addSql(<<<'SQL'
            CREATE TABLE IF NOT EXISTS priority (
                id INT AUTO_INCREMENT NOT NULL,
                title VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);

        // Insert default priority values
        $this->addSql(<<<'SQL'
            INSERT IGNORE INTO priority (title) VALUES ('Low'), ('Medium'), ('High')
        SQL);

        // Add the foreign key for priority_id if it doesn't already exist
        // Commenting out the line that causes duplicate FK creation
        // Check manually in the database if necessary
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)
        // SQL);

        // Add the index on priority_id if it doesn't already exist
        // Commenting out the line that causes duplicate index creation
        // $this->addSql(<<<'SQL'
        //     CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)
        // SQL);
    }

    public function down(Schema $schema): void
    {
        // Remove the foreign key and index for priority_id
        // Commenting out to avoid breaking database integrity
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9
        // SQL);
        // $this->addSql(<<<'SQL'
        //     DROP INDEX IDX_527EDB25497B19F9 ON task
        // SQL);

        // Drop the priority table if it exists
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS priority
        SQL);
    }
}
