<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824091524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EE1D56BCA76ED395 ON musique (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BCA76ED395');
        $this->addSql('DROP INDEX IDX_EE1D56BCA76ED395 ON musique');
        $this->addSql('ALTER TABLE musique DROP user_id');
    }
}
