<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922074317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE musique_user (musique_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3FE4FD7825E254A1 (musique_id), INDEX IDX_3FE4FD78A76ED395 (user_id), PRIMARY KEY(musique_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE musique_user ADD CONSTRAINT FK_3FE4FD7825E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musique_user ADD CONSTRAINT FK_3FE4FD78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique_user DROP FOREIGN KEY FK_3FE4FD7825E254A1');
        $this->addSql('ALTER TABLE musique_user DROP FOREIGN KEY FK_3FE4FD78A76ED395');
        $this->addSql('DROP TABLE musique_user');
    }
}
