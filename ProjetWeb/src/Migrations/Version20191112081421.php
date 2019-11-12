<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112081421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, command_ordered_at DATETIME NOT NULL, command_user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_product (command_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_3C20574E33E1689A (command_id), INDEX IDX_3C20574E4584665A (product_id), PRIMARY KEY(command_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, product_name VARCHAR(50) NOT NULL, product_price NUMERIC(10, 2) NOT NULL, product_inventory INT NOT NULL, product_description LONGTEXT NOT NULL, product_image_path VARCHAR(255) NOT NULL, is_orderable TINYINT(1) NOT NULL, INDEX IDX_D34A04AD14959723 (product_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, product_type_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, picture_id INT NOT NULL, comment_text LONGTEXT NOT NULL, comment_posted_at DATETIME NOT NULL, comment_modified_at DATETIME DEFAULT NULL, comment_user_id INT NOT NULL, is_visible TINYINT(1) NOT NULL, INDEX IDX_9474526CEE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_impression (comment_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_A6BA3B91F8697D13 (comment_id), INDEX IDX_A6BA3B91A3BA46B6 (impression_id), PRIMARY KEY(comment_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_type_id INT NOT NULL, event_name VARCHAR(50) NOT NULL, event_description LONGTEXT NOT NULL, event_image_path VARCHAR(255) DEFAULT NULL, event_location VARCHAR(50) NOT NULL, event_price INT DEFAULT NULL, event_date DATETIME NOT NULL, event_created_at DATETIME NOT NULL, event_modified_at DATETIME DEFAULT NULL, event_created_by INT NOT NULL, event_is_visible TINYINT(1) NOT NULL, event_period VARCHAR(50) DEFAULT NULL, INDEX IDX_3BAE0AA7401B253C (event_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_impression (event_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_7CB5689E71F7E88B (event_id), INDEX IDX_7CB5689EA3BA46B6 (impression_id), PRIMARY KEY(event_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, event_type_name VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE impression (id INT AUTO_INCREMENT NOT NULL, impression_user_id INT NOT NULL, impression_type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, participation_user_id INT NOT NULL, INDEX IDX_AB55E24F71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, picture_name VARCHAR(50) DEFAULT NULL, picture_description LONGTEXT DEFAULT NULL, picture_posted_at DATETIME NOT NULL, picture_modified_at DATETIME DEFAULT NULL, picture_path VARCHAR(255) NOT NULL, picture_user_id INT NOT NULL, is_visible TINYINT(1) NOT NULL, INDEX IDX_16DB4F8971F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture_impression (picture_id INT NOT NULL, impression_id INT NOT NULL, INDEX IDX_88BF1B6EE45BDBF (picture_id), INDEX IDX_88BF1B6A3BA46B6 (impression_id), PRIMARY KEY(picture_id, impression_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E33E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE comment_impression ADD CONSTRAINT FK_A6BA3B91F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_impression ADD CONSTRAINT FK_A6BA3B91A3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event_impression ADD CONSTRAINT FK_7CB5689E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_impression ADD CONSTRAINT FK_7CB5689EA3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8971F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE picture_impression ADD CONSTRAINT FK_88BF1B6EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_impression ADD CONSTRAINT FK_88BF1B6A3BA46B6 FOREIGN KEY (impression_id) REFERENCES impression (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E33E1689A');
        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD14959723');
        $this->addSql('ALTER TABLE comment_impression DROP FOREIGN KEY FK_A6BA3B91F8697D13');
        $this->addSql('ALTER TABLE event_impression DROP FOREIGN KEY FK_7CB5689E71F7E88B');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E88B');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8971F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7401B253C');
        $this->addSql('ALTER TABLE comment_impression DROP FOREIGN KEY FK_A6BA3B91A3BA46B6');
        $this->addSql('ALTER TABLE event_impression DROP FOREIGN KEY FK_7CB5689EA3BA46B6');
        $this->addSql('ALTER TABLE picture_impression DROP FOREIGN KEY FK_88BF1B6A3BA46B6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CEE45BDBF');
        $this->addSql('ALTER TABLE picture_impression DROP FOREIGN KEY FK_88BF1B6EE45BDBF');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE command_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_impression');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_impression');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE impression');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE picture_impression');
    }
}
