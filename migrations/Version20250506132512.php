<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250506132512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add color column, unique index on title, and modify task table to remove priority_id';
    }

    public function up(Schema $schema): void
    {

    }

    public function down(Schema $schema): void
    {
        // Drop the unique index on 'title' column
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_62A6DC272B36786B ON priority
        SQL);

        // Drop the color column from the priority table
        $this->addSql(<<<'SQL'
            ALTER TABLE priority DROP color
        SQL);

        // Add the priority_id column back to task table
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD priority_id INT NOT NULL
        SQL);

        // Recreate the foreign key constraint on task table
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);

        // Recreate the index on priority_id in task table
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)
        SQL);
    }
}
