<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250518144016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD priority_id INT DEFAULT NULL, DROP priority
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
            ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_527EDB25497B19F9 ON task
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD priority VARCHAR(50) NOT NULL, DROP priority_id
        SQL);
    }
}
