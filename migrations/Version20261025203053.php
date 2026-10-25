<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261025203053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campagne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, type_campagne_id INT NOT NULL, groupement_id INT NOT NULL, INDEX IDX_539B5D165D3BA4E5 (type_campagne_id), INDEX IDX_539B5D16E66695CE (groupement_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cotisation (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATETIME NOT NULL, seance_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_AE64D2EDE3797A94 (seance_id), INDEX IDX_AE64D2ED6A99F74A (membre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE groupement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, nom_complet VARCHAR(150) NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, role_id INT NOT NULL, groupement_id INT NOT NULL, INDEX IDX_F6B4FB29D60322AC (role_id), INDEX IDX_F6B4FB29E66695CE (groupement_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mode_cotisation (id INT AUTO_INCREMENT NOT NULL, is_libre TINYINT NOT NULL, campagne_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_6BB9377E16227374 (campagne_id), INDEX IDX_6BB9377E6A99F74A (membre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, date_seance DATETIME NOT NULL, frequence VARCHAR(20) NOT NULL, campagne_id INT NOT NULL, INDEX IDX_DF7DFD0E16227374 (campagne_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_campagne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D165D3BA4E5 FOREIGN KEY (type_campagne_id) REFERENCES type_campagne (id)');
        $this->addSql('ALTER TABLE campagne ADD CONSTRAINT FK_539B5D16E66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id)');
        $this->addSql('ALTER TABLE cotisation ADD CONSTRAINT FK_AE64D2EDE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE cotisation ADD CONSTRAINT FK_AE64D2ED6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29E66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id)');
        $this->addSql('ALTER TABLE mode_cotisation ADD CONSTRAINT FK_6BB9377E16227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id)');
        $this->addSql('ALTER TABLE mode_cotisation ADD CONSTRAINT FK_6BB9377E6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E16227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D165D3BA4E5');
        $this->addSql('ALTER TABLE campagne DROP FOREIGN KEY FK_539B5D16E66695CE');
        $this->addSql('ALTER TABLE cotisation DROP FOREIGN KEY FK_AE64D2EDE3797A94');
        $this->addSql('ALTER TABLE cotisation DROP FOREIGN KEY FK_AE64D2ED6A99F74A');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29D60322AC');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29E66695CE');
        $this->addSql('ALTER TABLE mode_cotisation DROP FOREIGN KEY FK_6BB9377E16227374');
        $this->addSql('ALTER TABLE mode_cotisation DROP FOREIGN KEY FK_6BB9377E6A99F74A');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E16227374');
        $this->addSql('DROP TABLE campagne');
        $this->addSql('DROP TABLE cotisation');
        $this->addSql('DROP TABLE groupement');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE mode_cotisation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE type_campagne');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
