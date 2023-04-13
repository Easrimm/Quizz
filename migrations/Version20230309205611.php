<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309205611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bannissement (id INT AUTO_INCREMENT NOT NULL, banni_id INT NOT NULL, banneur_id INT NOT NULL, raison LONGTEXT NOT NULL, date_fin DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_906F309811FF3C53 (banni_id), INDEX IDX_906F30985255298E (banneur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bannissement ADD CONSTRAINT FK_906F309811FF3C53 FOREIGN KEY (banni_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE bannissement ADD CONSTRAINT FK_906F30985255298E FOREIGN KEY (banneur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur DROP is_banned');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bannissement DROP FOREIGN KEY FK_906F309811FF3C53');
        $this->addSql('ALTER TABLE bannissement DROP FOREIGN KEY FK_906F30985255298E');
        $this->addSql('DROP TABLE bannissement');
        $this->addSql('ALTER TABLE utilisateur ADD is_banned TINYINT(1) NOT NULL');
    }
}
