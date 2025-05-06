<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506075346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE priority_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD priority_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE priority_task (priority_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_9A0676FC497B19F9 (priority_id), INDEX IDX_9A0676FC8DB60186 (task_id), PRIMARY KEY(task_id, priority_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_527EDB25497B19F9 ON task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP priority_id
        SQL);
    }
}
