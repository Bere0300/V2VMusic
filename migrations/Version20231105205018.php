<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105205018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, musique_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, contenu LONGTEXT DEFAULT NULL, sujet VARCHAR(255) NOT NULL, INDEX IDX_F4B5511425E254A1 (musique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511425E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC25E254A1');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC25E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511425E254A1');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC25E254A1');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC25E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
    }
}
