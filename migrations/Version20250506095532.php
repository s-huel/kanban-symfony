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
        // Create priority table if it doesn't exist
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

        // Ensure the foreign key for priority_id exists in the task table
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)
        SQL);

        // Create an index on priority_id
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Remove the foreign key and index for priority_id
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_527EDB25497B19F9 ON task
        SQL);

        // Drop the priority table
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS priority
        SQL);
    }
}
