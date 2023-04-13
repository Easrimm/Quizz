<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303233110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_proposition (utilisateur_id INT NOT NULL, proposition_id INT NOT NULL, INDEX IDX_332BCEC0FB88E14F (utilisateur_id), INDEX IDX_332BCEC0DB96F9E (proposition_id), PRIMARY KEY(utilisateur_id, proposition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_utilisateur (utilisateur_source INT NOT NULL, utilisateur_target INT NOT NULL, INDEX IDX_E9FA6E203E2745F8 (utilisateur_source), INDEX IDX_E9FA6E2027C21577 (utilisateur_target), PRIMARY KEY(utilisateur_source, utilisateur_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_proposition ADD CONSTRAINT FK_332BCEC0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_proposition ADD CONSTRAINT FK_332BCEC0DB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_utilisateur ADD CONSTRAINT FK_E9FA6E203E2745F8 FOREIGN KEY (utilisateur_source) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_utilisateur ADD CONSTRAINT FK_E9FA6E2027C21577 FOREIGN KEY (utilisateur_target) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur_proposition DROP FOREIGN KEY FK_332BCEC0FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_proposition DROP FOREIGN KEY FK_332BCEC0DB96F9E');
        $this->addSql('ALTER TABLE utilisateur_utilisateur DROP FOREIGN KEY FK_E9FA6E203E2745F8');
        $this->addSql('ALTER TABLE utilisateur_utilisateur DROP FOREIGN KEY FK_E9FA6E2027C21577');
        $this->addSql('DROP TABLE utilisateur_proposition');
        $this->addSql('DROP TABLE utilisateur_utilisateur');
    }
}
