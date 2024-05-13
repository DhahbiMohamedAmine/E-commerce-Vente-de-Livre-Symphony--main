<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509193935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1EC470631');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1FCDAEAAA');
        $this->addSql('DROP INDEX IDX_845CA2C1EC470631 ON order_details');
        $this->addSql('DROP INDEX IDX_845CA2C1FCDAEAAA ON order_details');
        $this->addSql('DROP INDEX `primary` ON order_details');
        $this->addSql('ALTER TABLE order_details ADD order_id INT NOT NULL, ADD livres_id INT NOT NULL, DROP id, DROP order_id_id, DROP livre_id_id, CHANGE price price INT NOT NULL');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C18D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1EBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C18D9F6D38 ON order_details (order_id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1EBF07F38 ON order_details (livres_id)');
        $this->addSql('ALTER TABLE order_details ADD PRIMARY KEY (order_id, livres_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C18D9F6D38');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1EBF07F38');
        $this->addSql('DROP INDEX IDX_845CA2C18D9F6D38 ON order_details');
        $this->addSql('DROP INDEX IDX_845CA2C1EBF07F38 ON order_details');
        $this->addSql('ALTER TABLE order_details ADD id INT AUTO_INCREMENT NOT NULL, ADD order_id_id INT DEFAULT NULL, ADD livre_id_id INT DEFAULT NULL, DROP order_id, DROP livres_id, CHANGE price price DOUBLE PRECISION NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1EC470631 FOREIGN KEY (livre_id_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1EC470631 ON order_details (livre_id_id)');
        $this->addSql('CREATE INDEX IDX_845CA2C1FCDAEAAA ON order_details (order_id_id)');
    }
}
