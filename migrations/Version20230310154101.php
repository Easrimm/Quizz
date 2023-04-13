<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310154101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD ban_recu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B37D0054FD FOREIGN KEY (ban_recu_id) REFERENCES bannissement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B37D0054FD ON utilisateur (ban_recu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B37D0054FD');
        $this->addSql('DROP INDEX UNIQ_1D1C63B37D0054FD ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP ban_recu_id');
    }
}
