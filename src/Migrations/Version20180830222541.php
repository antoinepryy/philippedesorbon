<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830222541 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE champagne (id INT AUTO_INCREMENT NOT NULL, title_1 VARCHAR(255) NOT NULL, subtitle_1 VARCHAR(255) NOT NULL, presentation_1 LONGTEXT NOT NULL, subtitle_2_1 VARCHAR(255) DEFAULT NULL, presentation_2_1 LONGTEXT DEFAULT NULL, subtitle_2_2 VARCHAR(255) DEFAULT NULL, presentation_2_2 LONGTEXT DEFAULT NULL, subtitle_2_3 VARCHAR(255) DEFAULT NULL, presentation_2_3 LONGTEXT DEFAULT NULL, title_3 VARCHAR(255) DEFAULT NULL, subtitle_4 VARCHAR(255) NOT NULL, presentation_4 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE champagne');
    }
}
