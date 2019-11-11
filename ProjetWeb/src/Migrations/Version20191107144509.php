<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107144509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD event_type_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA729A6C08F FOREIGN KEY (event_type_id_id) REFERENCES event_type (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA729A6C08F ON event (event_type_id_id)');
        $this->addSql('ALTER TABLE picture ADD event_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F893E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F893E5F2F7B ON picture (event_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA729A6C08F');
        $this->addSql('DROP INDEX IDX_3BAE0AA729A6C08F ON event');
        $this->addSql('ALTER TABLE event DROP event_type_id_id');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F893E5F2F7B');
        $this->addSql('DROP INDEX IDX_16DB4F893E5F2F7B ON picture');
        $this->addSql('ALTER TABLE picture DROP event_id_id');
    }
}
