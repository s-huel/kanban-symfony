<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506093408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Commenting out the addition of the `id` column since it already exists
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE tag_task ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        // SQL);

        // Commenting out the foreign key addition since it may already exist
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)
        // SQL);

        // Commenting out the index creation since it may already exist
        // $this->addSql(<<<'SQL'
        //     CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)
        // SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // Commenting out the removal of the `id` column
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE tag_task MODIFY id INT NOT NULL
        // SQL);
        // $this->addSql(<<<'SQL'
        //     DROP INDEX `PRIMARY` ON tag_task
        // SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE tag_task DROP id
        // SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE tag_task ADD PRIMARY KEY (task_id, tag_id)
        // SQL);

        // Commenting out the foreign key removal
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9
        // SQL);

        // Commenting out the index removal
        // $this->addSql(<<<'SQL'
        //     DROP INDEX IDX_527EDB25497B19F9 ON task
        // SQL);
    }
}
