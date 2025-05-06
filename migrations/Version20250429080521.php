<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429080521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task DROP FOREIGN KEY FK_BC7164938DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task DROP FOREIGN KEY FK_BC716493BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON tag_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD CONSTRAINT FK_BC7164938DB60186 FOREIGN KEY (task_id) REFERENCES task (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD CONSTRAINT FK_BC716493BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD PRIMARY KEY (task_id, tag_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON priority_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD PRIMARY KEY (task_id, priority_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task DROP FOREIGN KEY FK_9A0676FC497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON priority_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD CONSTRAINT FK_9A0676FC497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE priority_task ADD PRIMARY KEY (priority_id, task_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task DROP FOREIGN KEY FK_BC7164938DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task DROP FOREIGN KEY FK_BC716493BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON tag_task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD CONSTRAINT FK_BC7164938DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD CONSTRAINT FK_BC716493BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_task ADD PRIMARY KEY (tag_id, task_id)
        SQL);
    }
}
