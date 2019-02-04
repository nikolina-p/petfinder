<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203134341 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pet ADD gender VARCHAR(20) NOT NULL, ADD breed VARCHAR(50) NOT NULL');
        //$this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        //$this->addSql('CREATE INDEX IDX_E4529B85B2A1D860 ON pet (species_id)');
        //$this->addSql('ALTER TABLE pet RENAME INDEX fk_e4529b857e3c61f9 TO IDX_E4529B857E3C61F9');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B85B2A1D860');
        //$this->addSql('DROP INDEX IDX_E4529B85B2A1D860 ON pet');
        $this->addSql('ALTER TABLE pet DROP gender, DROP breed');
        //$this->addSql('ALTER TABLE pet RENAME INDEX idx_e4529b857e3c61f9 TO FK_E4529B857E3C61F9');
    }
}
