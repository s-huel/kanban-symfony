<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250506131322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add color column, unique index on title, and modify task table to remove priority_id';
    }

    public function up(Schema $schema): void
    {
        // Check if the 'color' column already exists in the 'priority' table before attempting to add it.
        $columnExists = $this->connection->fetchOne("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'priority' AND column_name = 'color'");
        if ((int) $columnExists === 0) {
            $this->addSql("ALTER TABLE priority ADD color VARCHAR(7) DEFAULT NULL;");
        }

        // Check if the unique index already exists
        $indexExists = $this->connection->fetchOne("SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_name = 'priority' AND index_name = 'UNIQ_62A6DC272B36786B'");
        if ((int) $indexExists === 0) {
            $this->addSql("CREATE UNIQUE INDEX UNIQ_62A6DC272B36786B ON priority (title);");
        }

        // Drop the foreign key from the 'task' table
        $this->addSql("ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9;");

        // Drop the index on 'priority_id' from the 'task' table
        $this->addSql("DROP INDEX IDX_527EDB25497B19F9 ON task;");

        // Drop the 'priority_id' column from the 'task' table
        $this->addSql("ALTER TABLE task DROP priority_id;");
    }

    public function down(Schema $schema): void
    {
        // Drop the unique index from the 'priority' table
        $this->addSql("DROP INDEX UNIQ_62A6DC272B36786B ON priority;");

        // Drop the 'color' column from the 'priority' table (if it exists)
        $this->addSql("ALTER TABLE priority DROP COLUMN color;");

        // Add the 'priority_id' column back to the 'task' table
        $this->addSql("ALTER TABLE task ADD priority_id INT NOT NULL;");

        // Recreate the foreign key constraint on the 'task' table
        $this->addSql("ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE NO ACTION;");

        // Recreate the index on 'priority_id' in the 'task' table
        $this->addSql("CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id);");
    }
}
